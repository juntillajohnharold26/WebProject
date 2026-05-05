<?php

namespace App\Http\Controllers;

use App\Models\TemplateListing;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    private function addPurchaseNotice(TemplateListing $listing, int $quantity = 1): void
    {
        $purchaseId = uniqid('purchase_', true);
        $purchasedAt = now();
        $total = (float) $listing->price * $quantity;

        $purchase = [
            'id' => $purchaseId,
            'listing_id' => $listing->id,
            'title' => $listing->title,
            'category' => $listing->category,
            'seller' => $listing->user?->devsell_display_name ?? $listing->user?->name ?? 'Seller',
            'quantity' => $quantity,
            'total' => $total,
            'status' => 'Completed',
            'purchased_at' => $purchasedAt->toDateTimeString(),
        ];

        $notification = [
            'id' => $purchaseId,
            'title' => 'Purchase confirmed',
            'body' => "You purchased {$listing->title}. Your download is ready in Purchases.",
            'badge' => 'New',
            'badge_class' => 'bg-success',
            'time' => 'Just now',
            'created_at' => $purchasedAt->toDateTimeString(),
        ];

        $message = [
            'id' => $purchaseId,
            'sender' => 'DevBuy Orders',
            'subject' => 'Purchase confirmed',
            'preview' => "You purchased {$listing->title}.",
            'body' => "Thanks for your purchase. Your order for {$listing->title} has been confirmed and added to your purchased assets.",
            'time' => 'Just now',
            'created_at' => $purchasedAt->toDateTimeString(),
        ];

        session([
            'purchases' => array_values(array_merge([$purchase], session('purchases', []))),
            'notifications' => array_values(array_merge([$notification], session('notifications', []))),
            'messages' => array_values(array_merge([$message], session('messages', []))),
        ]);
    }

    public function index(Request $request)
    {
        $query = TemplateListing::where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $listings = $query->with('user')->latest()->paginate(12);

        return view('explore', compact('listings'));
    }

    public function search(Request $request)
    {
        $query = TemplateListing::where('status', 'active');

        if ($request->filled('q')) {
            $searchTerm = strtolower($request->q);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(tags) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        if ($request->filled('tag')) {
            $tag = strtolower($request->tag);
            $query->whereRaw('LOWER(tags) LIKE ?', ["%{$tag}%"]);
        }

        // Filters
        if ($request->filled('category')) {
            $query->whereRaw('LOWER(category) = ?', [strtolower($request->category)]);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $listings = $query->with('user')->latest()->paginate(12);

        $categories = TemplateListing::where('status', 'active')->distinct()->pluck('category');

        $minPrice = TemplateListing::where('status', 'active')->min('price');
        $maxPrice = TemplateListing::where('status', 'active')->max('price');

        $tags = TemplateListing::where('status', 'active')
            ->pluck('tags')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('search', compact('listings', 'categories', 'minPrice', 'maxPrice', 'tags'));
    }

    public function show(TemplateListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }

        $listing->load('user');

        return view('market.templates.show', compact('listing'));
    }

    public function cart(Request $request)
    {
        $cart = session('cart', []);
        $listingIds = array_keys($cart);

        $listings = TemplateListing::whereIn('id', $listingIds)
            ->where('status', 'active')
            ->with('user')
            ->get();

        $total = 0;
        foreach ($listings as $listing) {
            $total += $listing->price * ($cart[$listing->id] ?? 1);
        }

        return view('cart', compact('listings', 'cart', 'total'));
    }

    public function purchases()
    {
        return view('purchases', [
            'purchases' => session('purchases', []),
        ]);
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $listingIds = array_keys($cart);

        if (empty($listingIds)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $listings = TemplateListing::whereIn('id', $listingIds)
            ->where('status', 'active')
            ->with('user')
            ->get();

        foreach ($listings as $listing) {
            $this->addPurchaseNotice($listing, $cart[$listing->id] ?? 1);
        }

        session()->forget('cart');

        return redirect()
            ->route('purchases')
            ->with('success', 'Purchase complete. We added a confirmation to your notifications and inbox.');
    }

    public function buyNow(TemplateListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }

        $listing->load('user');
        $this->addPurchaseNotice($listing);

        return redirect()
            ->route('purchases')
            ->with('success', 'Purchase complete. We added a confirmation to your notifications and inbox.');
    }

    public function addToCart(Request $request, TemplateListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }

        $cart = session('cart', []);

        if (isset($cart[$listing->id])) {
            $cart[$listing->id]++;
        } else {
            $cart[$listing->id] = 1;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Added to cart successfully.');
    }

    public function removeFromCart(Request $request, TemplateListing $listing)
    {
        $cart = session('cart', []);

        unset($cart[$listing->id]);

        session(['cart' => $cart]);

        return back()->with('success', 'Removed from cart.');
    }
}
