<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\TemplateListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
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

        $listings = TemplateListing::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('seller.templates.index', compact('listings'));
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
        $data['tags'] = !empty($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : [];

        // Create user dir
        $userDir = 'templates/' . $userId;
        $slug = Str::slug($data['title'] . '-' . time());
        $zipPath = $userDir . '/' . $slug . '.zip';
        Storage::disk('public')->makeDirectory($userDir);
        $request->file('zip')->storeAs($userDir, $slug . '.zip', 'public');

        $previewPaths = [];
        if ($request->hasFile('preview_images')) {
            foreach ($request->file('preview_images') as $image) {
                $previewName = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $previewPaths[] = $image->storeAs($userDir . '/previews', $previewName, 'public');
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
        $data['tags'] = !empty($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : [];

        if ($request->boolean('delete_zip')) {
            Storage::disk('public')->delete($template->zip_path);
            $template->zip_path = null;
        } elseif ($request->hasFile('zip')) {
            $userDir = $template->zip_path ? dirname($template->zip_path) : 'templates/' . $userId;
            $slug = Str::slug($data['title'] . '-' . time());
            $newZip = $userDir . '/' . $slug . '.zip';
            $request->file('zip')->storeAs($userDir, $slug . '.zip', 'public');
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
            Log::error('Template files delete failed: ' . $e->getMessage());
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
