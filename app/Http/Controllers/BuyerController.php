<?php

namespace App\Http\Controllers;

use App\Models\TemplateListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuyerController extends Controller
{
    private function addPurchaseNotice(TemplateListing $listing, int $quantity = 1, string $transactionId = '', string $paymentMethod = 'Credit Card', string $paymentStatus = 'Paid', string $orderStatus = 'Delivered'): void
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
            'status' => $paymentStatus,
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'purchased_at' => $purchasedAt->toDateTimeString(),
        ];

        $notification = [
            'id' => $purchaseId,
            'title' => "Payment {$paymentStatus}",
            'body' => "{$paymentStatus} payment for {$listing->title}. Transaction: {$transactionId}.",
            'badge' => $paymentStatus === 'Paid' ? 'Paid' : 'Pending',
            'badge_class' => $paymentStatus === 'Paid' ? 'bg-success' : 'bg-warning text-dark',
            'time' => 'Just now',
            'created_at' => $purchasedAt->toDateTimeString(),
        ];

        $message = [
            'id' => $purchaseId,
            'sender' => 'DevBuy Orders',
            'subject' => "Payment {$paymentStatus} for {$listing->title}",
            'preview' => "Your purchase of {$listing->title} is {$paymentStatus}.",
            'body' => "Thanks for your purchase. {$paymentStatus} {$paymentMethod} payment for {$listing->title}. Transaction ID: {$transactionId}.",
            'time' => 'Just now',
            'created_at' => $purchasedAt->toDateTimeString(),
        ];

        session([
            'purchases' => array_values(array_merge([$purchase], session('purchases', []))),
            'notifications' => array_values(array_merge([$notification], session('notifications', []))),
            'messages' => array_values(array_merge([$message], session('messages', []))),
        ]);
    }

    private function notifySeller(TemplateListing $listing, string $type, string $title, string $body, string $badge, string $badgeClass, ?int $quantity = null, ?float $total = null): void
    {
        $listing->user?->marketplaceNotifications()->create([
            'template_listing_id' => $listing->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'badge' => $badge,
            'badge_class' => $badgeClass,
            'quantity' => $quantity,
            'total' => $total,
        ]);
    }

    private function currentUserId(): ?int
    {
        $userId = session('user_id');

        return $userId ? (int) $userId : null;
    }

    private function isOwner(TemplateListing $listing): bool
    {
        return $this->currentUserId() !== null && $listing->user_id === $this->currentUserId();
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

        $listings = $query->with('user')
            ->withCount('reviews')
            ->withCount(['reviews as positive_reviews_count' => function ($query) {
                $query->where('rating', '>=', 4);
            }])
            ->withCount(['reviews as negative_reviews_count' => function ($query) {
                $query->where('rating', '<', 4);
            }])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(12);

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
                    ->orWhereHas('user', function ($u) use ($searchTerm) {
                        $u->whereRaw('LOWER(devsell_display_name) LIKE ?', ["%{$searchTerm}%"]) 
                          ->orWhereRaw('LOWER(devsell_store_name) LIKE ?', ["%{$searchTerm}%"]) 
                          ->orWhereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });
        }

        // Allow an explicit seller/shop filter (search by seller display name, store name or user name)
        if ($request->filled('seller')) {
            $sellerTerm = strtolower($request->seller);
            $query->whereHas('user', function ($u) use ($sellerTerm) {
                $u->whereRaw('LOWER(devsell_display_name) LIKE ?', ["%{$sellerTerm}%"]) 
                  ->orWhereRaw('LOWER(devsell_store_name) LIKE ?', ["%{$sellerTerm}%"]) 
                  ->orWhereRaw('LOWER(name) LIKE ?', ["%{$sellerTerm}%"]);
            });
        }
        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
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

        $listings = $query->with('user')
            ->withCount('reviews')
            ->withCount(['reviews as positive_reviews_count' => function ($query) {
                $query->where('rating', '>=', 4);
            }])
            ->withCount(['reviews as negative_reviews_count' => function ($query) {
                $query->where('rating', '<', 4);
            }])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(12);

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

        $listing->load(['user', 'likes', 'reviews.user']);

        return view('market.templates.show', compact('listing'));
    }

    public function seller(User $seller)
    {
        if (! $seller->devsell_active) {
            abort(404);
        }

        $listings = TemplateListing::where('user_id', $seller->id)
            ->where('status', 'active')
            ->with('user')
            ->withCount('reviews')
            ->withCount(['reviews as positive_reviews_count' => function ($query) {
                $query->where('rating', '>=', 4);
            }])
            ->withCount(['reviews as negative_reviews_count' => function ($query) {
                $query->where('rating', '<', 4);
            }])
            ->latest()
            ->paginate(12);

        return view('market.sellers.show', compact('seller', 'listings'));
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
        $purchases = collect(session('purchases', []))->map(function ($purchase) {
            return [
                'id' => $purchase['id'] ?? uniqid('purchase_', true),
                'listing_id' => $purchase['listing_id'] ?? null,
                'title' => $purchase['title'] ?? 'Unknown product',
                'category' => $purchase['category'] ?? 'Unknown',
                'seller' => $purchase['seller'] ?? 'Seller',
                'quantity' => $purchase['quantity'] ?? 1,
                'total' => $purchase['total'] ?? 0.0,
                'status' => $purchase['payment_status'] ?? $purchase['status'] ?? 'Paid',
                'payment_status' => $purchase['payment_status'] ?? $purchase['status'] ?? 'Paid',
                'order_status' => $purchase['order_status'] ?? 'Delivered',
                'payment_method' => $purchase['payment_method'] ?? 'Credit Card',
                'transaction_id' => $purchase['transaction_id'] ?? 'N/A',
                'purchased_at' => $purchase['purchased_at'] ?? now()->toDateTimeString(),
            ];
        })->all();

        return view('purchases', [
            'purchases' => $purchases,
        ]);
    }

    public function downloadPurchase(string $purchaseId)
    {
        $purchases = session('purchases', []);

        $purchase = collect($purchases)->firstWhere('id', $purchaseId);

        if (! $purchase) {
            return redirect()->route('purchases')->with('error', 'Purchase not found.');
        }

        $paymentStatus = $purchase['payment_status'] ?? $purchase['status'] ?? 'Pending';

        if (strtolower($paymentStatus) !== 'paid') {
            return redirect()->route('purchases')->with('error', 'Payment is not complete yet. Download will be available after payment clears.');
        }

        $listingId = $purchase['listing_id'] ?? null;

        if (! $listingId) {
            return redirect()->route('purchases')->with('error', 'No listing attached to this purchase.');
        }

        $listing = TemplateListing::find($listingId);

        if (! $listing || empty($listing->zip_path)) {
            return redirect()->route('purchases')->with('error', 'The file for this template is not available.');
        }

        // Serve the file from the public disk
        return Storage::disk('public')->download($listing->zip_path, basename($listing->zip_path));
    }

    public function checkout(Request $request)
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

        if ($this->currentUserId() !== null && $listings->contains(fn (TemplateListing $listing) => $listing->user_id === $this->currentUserId())) {
            return redirect()->route('cart')->with('error', 'Remove your own template from the cart before checking out.');
        }

        $total = 0;
        foreach ($listings as $listing) {
            $total += $listing->price * ($cart[$listing->id] ?? 1);
        }

        $transactionId = 'TXN'.strtoupper(substr(sha1(now()->timestamp.implode(',', $listingIds)), 0, 12));

        $paymentMethods = [
            'Credit Card' => 'Credit Card',
            'PayPal' => 'PayPal',
            'Bank transfer' => 'Bank transfer',
        ];

        return view('checkout', compact('listings', 'cart', 'total', 'transactionId', 'paymentMethods'));
    }

    public function processCheckout(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'in:Credit Card,PayPal,Bank transfer'],
        ]);

        $cart = session('cart', []);
        $listingIds = array_keys($cart);

        if (empty($listingIds)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $listings = TemplateListing::whereIn('id', $listingIds)
            ->where('status', 'active')
            ->with('user')
            ->get();

        if ($this->currentUserId() !== null && $listings->contains(fn (TemplateListing $listing) => $listing->user_id === $this->currentUserId())) {
            return redirect()->route('cart')->with('error', 'Remove your own template from the cart before completing checkout.');
        }

        $paymentStatus = $data['payment_method'] === 'Bank transfer' ? 'Pending' : 'Paid';
        $orderStatus = $paymentStatus === 'Paid' ? 'Delivered' : 'Processing';

        foreach ($listings as $listing) {
            $quantity = $cart[$listing->id] ?? 1;
            $lineTotal = (float) $listing->price * $quantity;
            $sellerRevenue = $paymentStatus === 'Paid' ? $lineTotal : 0.0;

            $this->addPurchaseNotice(
                $listing,
                $quantity,
                $data['transaction_id'],
                $data['payment_method'],
                $paymentStatus,
                $orderStatus,
            );

            $this->notifySeller(
                $listing,
                'template_purchased',
                'Template purchased',
                "Someone bought {$listing->title}. Transaction: {$data['transaction_id']}.",
                'Sale',
                'bg-success',
                $quantity,
                $sellerRevenue,
            );
        }

        session()->forget('cart');

        $message = $paymentStatus === 'Paid'
            ? 'Payment complete. We added a confirmation to your notifications and inbox.'
            : 'Order received. Payment is pending, and we will update you when it clears.';

        return redirect()
            ->route('purchases')
            ->with('success', $message);
    }

    public function buyNow(TemplateListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }

        if ($listing->status !== 'active') {
            abort(404);
        }

        if ($this->isOwner($listing)) {
            return redirect()->route('templates.show', $listing)->with('error', 'You cannot purchase your own template.');
        }

        $cart = session('cart', []);
        if (! isset($cart[$listing->id])) {
            $cart[$listing->id] = 1;
            session(['cart' => $cart]);
        }

        return redirect()
            ->route('cart.checkout')
            ->with('success', 'Ready for checkout. Choose your payment method.');
    }

    public function addToCart(Request $request, TemplateListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }

        if ($this->isOwner($listing)) {
            return back()->with('error', 'You cannot add your own template to the cart.');
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

    public function toggleLike(Request $request, TemplateListing $listing)
    {
        $userId = $this->currentUserId();

        if (! $userId) {
            return redirect()->route('login')->with('error', 'You must be logged in to like templates.');
        }

        if ($this->isOwner($listing)) {
            return back()->with('error', 'You cannot like your own template.');
        }

        $like = $listing->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $message = 'Like removed.';
        } else {
            $listing->likes()->create(['user_id' => $userId]);
            $message = 'Template liked!';
        }

        return back()->with('success', $message);
    }

    public function storeReview(Request $request, TemplateListing $listing)
    {
        $userId = $this->currentUserId();

        if (! $userId) {
            return redirect()->route('login')->with('error', 'You must be logged in to review templates.');
        }

        if ($this->isOwner($listing)) {
            return back()->with('error', 'You cannot review your own template.');
        }

        $request->validate([
            'rating' => 'required|integer|in:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $listing->reviews()->updateOrCreate(
            ['user_id' => $userId],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        $reaction = (int) $request->rating >= 4 ? 'thumbs up' : 'thumbs down';

        $this->notifySeller(
            $listing,
            'template_reviewed',
            'New review',
            "Someone left a {$reaction} review on {$listing->title}.",
            'Review',
            (int) $request->rating >= 4 ? 'bg-success' : 'bg-danger',
        );

        return back()->with('success', 'Review submitted successfully!');
    }
}
