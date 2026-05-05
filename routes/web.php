<?php

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

$getCurrentUser = function (): ?User {
    $userId = session('user_id');
    return $userId ? User::find($userId) : null;
};

$getSellerApplication = function (?User $user): array {
    return [
        'display_name' => $user?->devsell_display_name ?? $user?->name ?? '',
        'email' => $user?->email ?? '',
        'store_name' => $user?->devsell_store_name ?? '',
        'specialty' => $user?->devsell_specialty ?? '',
        'portfolio' => $user?->devsell_portfolio ?? '',
        'bio' => $user?->devsell_bio ?? '',
    ];
};

$getSellerAccess = function (?User $user): array {
    return [
        'active' => (bool) ($user?->devsell_active ?? false),
        'joined_at' => $user?->devsell_joined_at?->format('F j, Y'),
    ];
};

$statusOptions = [
    'active' => 'Active',
    'inactive' => 'Inactive',
    'busy' => 'Busy',
];

$getAccountProfile = function (?User $user): array {
    return [
        'name' => $user?->name ?? 'Guest',
        'email' => $user?->email ?? 'guest@gmail.com',
        'role' => $user?->profile_role ?? 'Your Role',
        'location' => $user?->profile_location ?? 'Your Location',
        'bio' => $user?->profile_bio ?? 'Your Bio.',
        'avatar' => $user?->profile_avatar ?? 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg',
        'status' => $user?->profile_status ?? 'active',
    ];
};

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (! $user) {
        return redirect()->route('signup')->with('error', 'No account found. Please sign up first.');
    }

    if (! Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors(['password' => 'Invalid credentials.'])->withInput();
    }

    session(['authenticated' => true, 'user_id' => $user->id]);

    return $user->is_admin ? redirect('/admin') : redirect('/explore');
})->name('login.submit');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'email' => ['required', 'email', 'max:100', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6'],
    ], [
        'email.unique' => 'This email is already registered. Please log in or use a different email.',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    session(['authenticated' => true, 'user_id' => $user->id]);

    return redirect('/explore');
})->name('signup.submit');

Route::match(['get', 'post'], '/logout', function () {
    session()->forget(['authenticated', 'user_id']);
    return redirect()->route('login');
})->name('logout');

Route::get('/', function () {
    return view('intro');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/explore', function (Request $request) {
    if (! session('authenticated')) {
        return redirect()->route('login')->with('error', 'Please log in or sign up to access Explore.');
    }

    return app(BuyerController::class)->index($request);
})->name('explore');

Route::get('/search', [BuyerController::class, 'search'])->name('search');

Route::get('/notifications', function (Request $request) {
    if (! session('authenticated')) {
        return redirect()->route('signup')->with('error', 'Please log in or sign up to view notifications.');
    }

    return view('notifications', [
        'notifications' => session('notifications', []),
    ]);
});

Route::get('/inbox', function (Request $request) {
    if (! session('authenticated')) {
        return redirect()->route('signup')->with('error', 'Please log in or sign up to view your inbox.');
    }

    return view('inbox', [
        'messages' => session('messages', []),
    ]);
});

Route::get('/messages/{id}', function (Request $request, $id) {
    if (! session('authenticated')) {
        return redirect()->route('signup')->with('error', 'Please log in or sign up to view this message.');
    }

    $messages = collect(session('messages', []))->keyBy('id')->all() + [
        1 => [
            'sender' => 'DevSell Team',
            'subject' => 'Order confirmed',
            'preview' => 'Your order for the E-commerce UI Kit has been confirmed.',
            'body' => 'Your order for the E-commerce UI Kit has been confirmed and is now being prepared for delivery. Check your inbox for the invoice and next steps.',
            'time' => '10 minutes ago',
        ],
        2 => [
            'sender' => 'Support',
            'subject' => 'Billing update',
            'preview' => 'Your payment method was successfully updated.',
            'body' => 'Your payment method was successfully updated. No action is needed unless you want to choose a different card.',
            'time' => 'Yesterday',
        ],
        3 => [
            'sender' => 'DevBuy Team',
            'subject' => 'New feature',
            'preview' => 'We added new dashboard templates to the Explore page.',
            'body' => 'We added new dashboard templates to the Explore page. Visit your inbox to see the new collections and start using them today.',
            'time' => '2 days ago',
        ],
    ];

    if (! isset($messages[$id])) {
        return redirect('/inbox');
    }

    return view('message', [
        'message' => $messages[$id],
    ]);
});

Route::get('/contact', function () use ($getCurrentUser, $getSellerAccess) {
    return view('contact', [
        'sellerActive' => $getSellerAccess($getCurrentUser())['active'],
    ]);
});

Route::get('/devsell/join', function () use ($getCurrentUser, $getSellerApplication, $getSellerAccess) {
    $user = $getCurrentUser();

    return view('devsell', [
        'application' => $getSellerApplication($user),
        'sellerAccess' => $getSellerAccess($user),
    ]);
})->name('devsell.join');

Route::post('/devsell/join', function (Request $request) use ($getCurrentUser) {
    $data = $request->validate([
        'display_name' => ['required', 'string', 'max:60'],
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
        'store_name' => ['required', 'string', 'max:80'],
        'specialty' => ['required', 'string', 'max:80'],
        'portfolio' => ['nullable', 'url', 'max:255'],
        'bio' => ['required', 'string', 'max:240'],
    ]);

    $user = User::where('email', $data['email'])->first();
    $sessionUser = $getCurrentUser();

    if (! $user || ! Hash::check($data['password'], $user->password)) {
        return back()
            ->withErrors(['password' => 'Enter your DevBuy email and password to unlock DevSell access.'])
            ->withInput();
    }

    if ($sessionUser && $sessionUser->id !== $user->id) {
        return back()
            ->withErrors(['email' => 'Use the account you are currently logged into, or log out first.'])
            ->withInput();
    }

    $user->forceFill([
        'devsell_active' => true,
        'devsell_joined_at' => $user->devsell_joined_at ?? now(),
        'devsell_display_name' => $data['display_name'],
        'devsell_store_name' => $data['store_name'],
        'devsell_specialty' => $data['specialty'],
        'devsell_portfolio' => $data['portfolio'] ?? '',
        'devsell_bio' => $data['bio'],
    ])->save();

    session([
        'authenticated' => true,
        'user_id' => $user->id,
    ]);

    return redirect()
        ->route('devsell.join')
        ->with('success', 'DevSell access unlocked. Your seller profile is ready.');
})->name('devsell.join.submit');

Route::get('/feedback', function () {
    return view('feedback');
});

Route::get('/account', function () use ($getCurrentUser, $getAccountProfile, $getSellerAccess, $statusOptions) {
    $user = $getCurrentUser();

    return view('accinfo', [
        'profile' => $getAccountProfile($user),
        'sellerAccess' => $getSellerAccess($user),
        'statusOptions' => $statusOptions,
    ]);
});

Route::redirect('/accinfo', '/account');

Route::post('/account', function (Request $request) use ($getCurrentUser, $statusOptions) {
    $user = $getCurrentUser();

    if (! $user) {
        return redirect()->route('login')->with('error', 'Please log in to update your account.');
    }

    $profile = $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
        'role' => ['required', 'string', 'max:80'],
        'location' => ['required', 'string', 'max:80'],
        'bio' => ['required', 'string', 'max:200'],
        'avatar' => ['nullable', 'url', 'max:255'],
        'status' => ['required', Rule::in(array_keys($statusOptions))],
    ]);

    $user->forceFill([
        'name' => $profile['name'],
        'email' => $profile['email'],
        'profile_role' => $profile['role'],
        'profile_location' => $profile['location'],
        'profile_bio' => $profile['bio'],
        'profile_avatar' => $profile['avatar'] ?: 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg',
        'profile_status' => $profile['status'],
    ])->save();

    return redirect('/account')->with('success', 'Profile updated successfully.');
});

Route::get('/purchases', [BuyerController::class, 'purchases'])->name('purchases');

Route::get('/templates/{listing}', [BuyerController::class, 'show'])->name('templates.show');

// Cart routes
Route::get('/cart', [BuyerController::class, 'cart'])->name('cart');
Route::post('/cart/checkout', [BuyerController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/add/{listing}', [BuyerController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{listing}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/templates/{listing}/buy', [BuyerController::class, 'buyNow'])->name('templates.buy');

Route::prefix('seller')->name('seller.')->group(function () {
    Route::resource('templates', \App\Http\Controllers\Seller\TemplateController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

Route::prefix('admin')->name('admin.')->middleware(AdminMiddleware::class)->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/templates/{listing}', [AdminController::class, 'show'])->name('templates.show');
    Route::post('/templates/{listing}/approve', [AdminController::class, 'approve'])->name('templates.approve');
    Route::post('/templates/{listing}/reject', [AdminController::class, 'reject'])->name('templates.reject');
});
