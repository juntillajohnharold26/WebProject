<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceNotification;
use App\Models\Review;
use App\Models\TemplateListing;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        if (! $userId || ! authSeller($userId)) {
            return redirect('/devsell/join')->with('error', 'Seller access required.');
        }

        $seller = User::find($userId);
        $listingIds = TemplateListing::where('user_id', $userId)->pluck('id');

        $totalTemplates = $listingIds->count();
        $activeTemplates = TemplateListing::where('user_id', $userId)->where('status', 'active')->count();
        $pendingTemplates = TemplateListing::where('user_id', $userId)->where('status', 'pending')->count();

        $totalReviews = Review::whereIn('template_listing_id', $listingIds)->count();
        $positiveReviews = Review::whereIn('template_listing_id', $listingIds)->where('rating', '>=', 4)->count();
        $negativeReviews = Review::whereIn('template_listing_id', $listingIds)->where('rating', '<', 4)->count();
        $averageRating = Review::whereIn('template_listing_id', $listingIds)->avg('rating') ?? 0;

        $saleNotifications = MarketplaceNotification::where('user_id', $userId)
            ->where('type', 'template_purchased')
            ->get(['quantity', 'total', 'created_at']);
        $salesCount = $saleNotifications->sum(fn ($sale) => $sale->quantity ?? 1);
        $totalRevenue = $saleNotifications->sum(fn ($sale) => (float) ($sale->total ?? 0));
        $recentSaleNotifications = $saleNotifications
            ->filter(fn ($sale) => $sale->created_at?->greaterThanOrEqualTo(now()->subDays(6)->startOfDay()));
        $recentSalesCount = $recentSaleNotifications->sum(fn ($sale) => $sale->quantity ?? 1);
        $recentRevenue = $recentSaleNotifications->sum(fn ($sale) => (float) ($sale->total ?? 0));
        $dailySales = collect(range(6, 0))->map(function ($daysAgo) use ($recentSaleNotifications) {
            $date = now()->subDays($daysAgo);
            $salesForDay = $recentSaleNotifications->filter(fn ($sale) => $sale->created_at?->isSameDay($date));

            return [
                'label' => $date->format('M j'),
                'sales' => $salesForDay->sum(fn ($sale) => $sale->quantity ?? 1),
                'revenue' => $salesForDay->sum(fn ($sale) => (float) ($sale->total ?? 0)),
            ];
        });

        $storeName = $seller?->devsell_store_name ?: $seller?->devsell_display_name ?: $seller?->name;
        $storeSpecialty = $seller?->devsell_specialty ?: 'Create and sell premium templates.';
        $storeBio = $seller?->devsell_bio ?: 'Tell buyers what makes your store unique.';

        $listings = TemplateListing::where('user_id', $userId)
            ->withCount('reviews')
            ->withCount(['reviews as positive_reviews_count' => function ($query) {
                $query->where('rating', '>=', 4);
            }])
            ->withCount(['reviews as negative_reviews_count' => function ($query) {
                $query->where('rating', '<', 4);
            }])
            ->withAvg('reviews', 'rating')
            ->with(['reviews' => function ($query) {
                $query->latest()->with('user')->take(2);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('seller.templates.index', compact(
            'listings',
            'totalTemplates',
            'activeTemplates',
            'pendingTemplates',
            'totalReviews',
            'positiveReviews',
            'negativeReviews',
            'averageRating',
            'salesCount',
            'totalRevenue',
            'recentSalesCount',
            'recentRevenue',
            'dailySales',
            'storeName',
            'storeSpecialty',
            'storeBio'
        ));
    }

    public function create()
    {
        $userId = session('user_id');
        if (! $userId || ! authSeller($userId)) {
            return redirect('/devsell/join')->with('error', 'Seller access required.');
        }

        return view('seller.templates.create');
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        if (! $userId || ! authSeller($userId)) {
            return redirect('/devsell/join')->with('error', 'Seller access required.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|in:UI Kit,Dashboard,Landing,E-commerce,Mobile,Components,Icon Pack',
            'tags' => 'nullable|string|max:500', // Accept string input
            'price' => 'required|numeric|min:0',
            'zip' => 'required|file|mimes:zip|max:51200', // 50MB
            'preview_images' => 'nullable|array|max:5',
            'preview_images.*' => 'image|max:5120', // 5MB per img
            'status' => Rule::in(['draft', 'active', 'archived']),
        ]);

        // Convert tags string to array
        $data['tags'] = ! empty($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : [];

        // Create user dir
        $userDir = 'templates/'.$userId;
        $slug = Str::slug($data['title'].'-'.time());
        $zipPath = $userDir.'/'.$slug.'.zip';
        Storage::disk('public')->makeDirectory($userDir);
        $request->file('zip')->storeAs($userDir, $slug.'.zip', 'public');

        $previewPaths = [];
        if ($request->hasFile('preview_images')) {
            foreach ($request->file('preview_images') as $image) {
                $previewName = Str::random(20).'.'.$image->getClientOriginalExtension();
                $previewPaths[] = $image->storeAs($userDir.'/previews', $previewName, 'public');
            }
        }

        $status = $data['status'] ?? 'draft';
        $wasSubmittedForReview = false;

        if ($status === 'active') {
            $status = 'pending';
            $wasSubmittedForReview = true;
        }

        TemplateListing::create([
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'tags' => $data['tags'] ?? [],
            'price' => $data['price'],
            'zip_path' => $zipPath,
            'preview_images' => $previewPaths,
            'status' => $status,
        ]);

        $message = $wasSubmittedForReview
            ? 'Template created and submitted for review. It will appear in the marketplace after admin approval.'
            : 'Template created.';

        return redirect()->route('seller.templates.index')->with('success', $message);

    }

    public function edit(TemplateListing $template)
    {
        $userId = session('user_id');
        if (! $userId || $template->user_id != $userId || ! authSeller($userId)) {
            abort(403);
        }

        return view('seller.templates.edit', compact('template'));
    }

    public function update(Request $request, TemplateListing $template)
    {
        $userId = session('user_id');
        if (! $userId || $template->user_id != $userId || ! authSeller($userId)) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|in:UI Kit,Dashboard,Landing,E-commerce,Mobile,Components,Icon Pack',
            'tags' => 'nullable|string|max:500', // Accept string input
            'price' => 'required|numeric|min:0',
            'status' => Rule::in(['active', 'draft', 'archived']),
            'zip' => 'nullable|file|mimes:zip|max:51200',
            'preview_images' => 'nullable|array|max:5',
            'preview_images.*' => 'image|max:5120',
            'delete_zip' => 'nullable',
            'delete_previews' => 'nullable|array',
        ]);

        // Convert tags string to array
        $data['tags'] = ! empty($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : [];

        if ($request->boolean('delete_zip')) {
            Storage::disk('public')->delete($template->zip_path);
            $template->zip_path = null;
        } elseif ($request->hasFile('zip')) {
            $userDir = $template->zip_path ? dirname($template->zip_path) : 'templates/'.$userId;
            $slug = Str::slug($data['title'].'-'.time());
            $newZip = $userDir.'/'.$slug.'.zip';
            $request->file('zip')->storeAs($userDir, $slug.'.zip', 'public');
            Storage::disk('public')->delete($template->zip_path);
            $template->zip_path = $newZip;
        }

        // Previews similar logic (omitted for brevity, add if needed)

        $status = $data['status'];
        $wasSubmittedForReview = false;

        if ($status === 'active') {
            $status = 'pending';
            $wasSubmittedForReview = true;
        }

        $template->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'tags' => $data['tags'] ?? [],
            'price' => $data['price'],
            'status' => $status,
        ]);

        $message = $wasSubmittedForReview
            ? 'Template updated and submitted for review. It will appear in the marketplace after admin approval.'
            : 'Template updated.';

        return redirect()->route('seller.templates.index')->with('success', $message);

    }

    public function destroy(TemplateListing $template)
    {
        $userId = session('user_id');
        if (! $userId || $template->user_id != $userId) {
            abort(403);
        }

        try {
            Storage::disk('public')->deleteDirectory(dirname(Storage::disk('public')->path($template->zip_path)));
        } catch (Exception $e) {
            Log::error('Template files delete failed: '.$e->getMessage());
        }
        $template->delete();

        return redirect()->route('seller.templates.index')->with('success', 'Template deleted.');
    }
}

function authSeller($userId): bool
{
    $user = User::find($userId);

    return $user && $user->devsell_active;
}
