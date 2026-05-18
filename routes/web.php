<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\Seller\TemplateController;
use App\Http\Middleware\AdminMiddleware;
use App\Mail\DevSellVerificationCode;
use App\Mail\SignupVerificationCode;
use App\Models\MarketplaceNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;


$signupVerificationSessionKey = 'signup_verification';
$devSellVerificationSessionKey = 'devsell_verification';

$getCurrentUser = function (): ?User {
    $userId = session('user_id');

    return $userId ? User::find($userId) : null;
};

$getSellerApplication = function (?User $user, ?array $pendingApplication = null): array {
    return [
        'display_name' => $pendingApplication['display_name'] ?? $user?->devsell_display_name ?? $user?->name ?? '',
        'email' => $pendingApplication['email'] ?? $user?->email ?? '',
        'store_name' => $pendingApplication['store_name'] ?? $user?->devsell_store_name ?? '',
        'specialty' => $pendingApplication['specialty'] ?? $user?->devsell_specialty ?? '',
        'portfolio' => $pendingApplication['portfolio'] ?? $user?->devsell_portfolio ?? '',
        'bio' => $pendingApplication['bio'] ?? $user?->devsell_bio ?? '',
    ];
};

$getSellerAccess = function (?User $user): array {
    return [
        'active' => (bool) ($user?->devsell_active ?? false),
        'status' => $user?->devsell_status ?? 'none',
        'pending' => ($user?->devsell_status ?? 'none') === 'pending',


        'joined_at' => $user?->devsell_joined_at?->format('F j, Y'),
        'display_name' => $user?->devsell_display_name ?? $user?->name ?? '',
        'store_name' => $user?->devsell_store_name ?? '',
        'specialty' => $user?->devsell_specialty ?? '',
        'portfolio' => $user?->devsell_portfolio ?? '',
        'bio' => $user?->devsell_bio ?? '',
    ];
};

$getPendingSignupVerification = function () use ($signupVerificationSessionKey): ?array {
    $verification = session($signupVerificationSessionKey);

    if (! is_array($verification)) {
        return null;
    }

    if (now()->timestamp > (int) ($verification['expires_at'] ?? 0)) {
        session()->forget($signupVerificationSessionKey);
        session()->flash('error', 'That signup verification code expired. Please sign up again.');

        return null;
    }

    return $verification;
};

$sendSignupVerificationCode = function (array $signup) use ($signupVerificationSessionKey): void {
    $code = (string) random_int(100000, 999999);

    Mail::to($signup['email'], $signup['name'])->send(new SignupVerificationCode($code));

    session([
        $signupVerificationSessionKey => [
            'name' => $signup['name'],
            'email' => $signup['email'],
            'password' => $signup['password'],
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->timestamp,
        ],
    ]);
};

$getPendingDevSellVerification = function () use ($devSellVerificationSessionKey): ?array {
    $verification = session($devSellVerificationSessionKey);

    if (! is_array($verification)) {
        return null;
    }

    if (now()->timestamp > (int) ($verification['expires_at'] ?? 0)) {
        session()->forget($devSellVerificationSessionKey);
        session()->flash('error', 'That DevSell verification code expired. Submit your seller details to request a new one.');

        return null;
    }

    return $verification;
};

$sendDevSellVerificationCode = function (User $user, array $application) use ($devSellVerificationSessionKey): void {
    $code = (string) random_int(100000, 999999);

    Mail::to($user->email, $user->name)->send(new DevSellVerificationCode($code));

    session([
        $devSellVerificationSessionKey => [
            'user_id' => $user->id,
            'email' => $user->email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->timestamp,
            'application' => $application,
        ],
    ]);
};

$activateDevSell = function (User $user, array $application, bool $markEmailVerified = false): void {
    $attributes = [
        'devsell_active' => true,
        'devsell_status' => 'approved',
        'devsell_joined_at' => $user->devsell_joined_at ?? now(),
        'devsell_display_name' => $application['display_name'],
        'devsell_store_name' => $application['store_name'],
        'devsell_specialty' => $application['specialty'],
        'devsell_portfolio' => $application['portfolio'] ?? '',
        'devsell_bio' => $application['bio'],
    ];

    if ($markEmailVerified && ! $user->email_verified_at) {
        $attributes['email_verified_at'] = now();
    }

    $user->forceFill($attributes)->save();
};

$submitDevSellForReview = function (User $user, array $application, bool $markEmailVerified = false): void {
    $attributes = [
        'devsell_active' => false,
        'devsell_status' => 'pending',
        'devsell_display_name' => $application['display_name'],
        'devsell_store_name' => $application['store_name'],
        'devsell_specialty' => $application['specialty'],
        'devsell_portfolio' => $application['portfolio'] ?? '',
        'devsell_bio' => $application['bio'],
    ];

    if ($markEmailVerified && ! $user->email_verified_at) {
        $attributes['email_verified_at'] = now();
    }

    $user->forceFill($attributes)->save();
};

$statusOptions = [
    'active' => 'Active',
    'inactive' => 'Inactive',
    'busy' => 'Busy',
];

$getAccountProfile = function (?User $user): array {
    return [
        'name' => $user?->name ?? '',
        'email' => $user?->email ?? '',
        'role' => null,
        'location' => null,
        'bio' => '',
        'avatar' => $user?->profile_avatar ?? 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg',
        'status' => null,
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

Route::post('/signup', function (Request $request) use ($sendSignupVerificationCode) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'email' => ['required', 'email', 'max:100', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6'],
    ], [
        'email.unique' => 'This email is already registered. Please log in or use a different email.',
    ]);

    $signup = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ];

    try {
        $sendSignupVerificationCode($signup);
    } catch (Throwable $exception) {
        report($exception);

        return back()
            ->withErrors(['email' => 'We could not send the signup verification code. Check your mail settings, Gmail address, and app password.'])
            ->withInput();
    }

    return redirect()
        ->route('signup.verify')
        ->with('success', 'We sent a 6-digit signup code to '.$signup['email'].'.');
})->name('signup.submit');

Route::get('/signup/verify', function () use ($getPendingSignupVerification) {
    $pendingVerification = $getPendingSignupVerification();

    if (! $pendingVerification) {
        return redirect()->route('signup')->with('error', session('error', 'Sign up first so we can send your verification code.'));
    }

    $expiresAt = (int) ($pendingVerification['expires_at'] ?? 0);

    return view('signup-verify', [
        'pendingVerification' => [
            'email' => $pendingVerification['email'] ?? '',
            'expires_at' => $expiresAt > 0
                ? Carbon::createFromTimestamp($expiresAt)->format('g:i A')
                : null,
        ],
        'email_verified_at' => now(),
    ]);
})->name('signup.verify');

Route::post('/signup/verify', function (Request $request) use ($signupVerificationSessionKey) {
    $data = $request->validate([
        'code' => ['required', 'digits:6'],
    ]);

    $verification = session($signupVerificationSessionKey);

    if (! is_array($verification)) {
        return redirect()
            ->route('signup')
            ->with('error', 'Sign up first so we can send your verification code.');
    }

    if (now()->timestamp > (int) ($verification['expires_at'] ?? 0)) {
        session()->forget($signupVerificationSessionKey);

        return redirect()
            ->route('signup')
            ->with('error', 'That signup verification code expired. Please sign up again.');
    }

    if (! Hash::check($data['code'], $verification['code_hash'] ?? '')) {
        return back()
            ->withErrors(['code' => 'That verification code is not correct.'])
            ->withInput();
    }

    if (User::where('email', $verification['email'] ?? '')->exists()) {
        session()->forget($signupVerificationSessionKey);

        return redirect()
            ->route('login')
            ->with('error', 'This email is already registered. Please log in.');
    }

    $user = User::create([
        'name' => $verification['name'],
        'email' => $verification['email'],
        'password' => $verification['password'],
    ]);

    $user->forceFill(['email_verified_at' => now()])->save();

    session()->forget($signupVerificationSessionKey);
    session(['authenticated' => true, 'user_id' => $user->id]);

    return redirect('/explore');
})->name('signup.verify.submit');

Route::post('/signup/verify/resend', function () use ($sendSignupVerificationCode, $signupVerificationSessionKey) {
    $verification = session($signupVerificationSessionKey);
    if (! is_array($verification)) {
        return redirect()
            ->route('signup')
            ->with('error', 'Sign up first so we can send your verification code.');
    }

    $signup = [
        'name' => $verification['name'],
        'email' => $verification['email'],
        'password' => $verification['password'],
    ];

    try {
        $sendSignupVerificationCode($signup);
    } catch (Throwable $exception) {
        report($exception);

        return redirect()
            ->route('signup.verify')
            ->withErrors(['email' => 'We could not send a fresh signup verification code. Check your mail settings and try again.']);
    }

    return redirect()
        ->route('signup.verify')
        ->with('success', 'We sent a fresh signup verification code to '.$signup['email'].'.');
})->name('signup.verify.resend');

Route::match(['get', 'post'], '/logout', function () use ($devSellVerificationSessionKey, $signupVerificationSessionKey) {
    session()->forget(['authenticated', 'user_id', $devSellVerificationSessionKey, $signupVerificationSessionKey]);

    return redirect()->route('login');
})->name('logout');

Route::get('/switch-account', function () use ($devSellVerificationSessionKey, $signupVerificationSessionKey) {
    session()->forget(['authenticated', 'user_id', $devSellVerificationSessionKey, $signupVerificationSessionKey]);
    return redirect()->route('login')->with('success', 'You can now log in with a different account.');
})->name('switch-account');

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

    $databaseNotifications = MarketplaceNotification::where('user_id', session('user_id'))
        ->latest()
        ->get()
        ->map(fn (MarketplaceNotification $notification) => [
            'title' => $notification->title,
            'body' => $notification->body,
            'badge' => $notification->badge,
            'badge_class' => $notification->badge_class,
            'time' => $notification->created_at->diffForHumans(),
        ])
        ->all();

    return view('notifications', [
        'notifications' => array_values(array_merge($databaseNotifications, session('notifications', []))),
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

    // Mark message as read in session (update by index to avoid Collection direct modification)
    $messagesCollection = collect(session('messages', []));
    $index = $messagesCollection->search(fn($m) => (string) ($m['id'] ?? '') === (string) $id);
    if ($index !== false) {
        $msg = $messagesCollection[$index];
        $msg['read'] = true;
        $messagesCollection[$index] = $msg;
        session(['messages' => $messagesCollection->values()->all()]);
    }

    return view('message', [
        'message' => $messages[$id],
    ]);
});

Route::post('/messages/{id}/delete', function (Request $request, $id) {
    if (! session('authenticated')) {
        return redirect()->route('signup')->with('error', 'Please log in or sign up to manage your messages.');
    }

    $messages = collect(session('messages', []));
    $filtered = $messages->reject(fn($m) => (string) ($m['id'] ?? '') === (string) $id)->values()->all();
    session(['messages' => $filtered]);

    return redirect('/inbox')->with('success', 'Message deleted.');
})->name('messages.delete');

Route::post('/messages/{id}/toggle-read', function (Request $request, $id) {
    if (! session('authenticated')) {
        return redirect()->route('signup')->with('error', 'Please log in or sign up to manage your messages.');
    }

    $messages = collect(session('messages', []))->map(function ($m) use ($id) {
        if ((string) ($m['id'] ?? '') === (string) $id) {
            $m['read'] = empty($m['read']) ? true : false;
        }
        return $m;
    })->values()->all();

    session(['messages' => $messages]);

    return redirect('/inbox');
})->name('messages.toggle-read');

Route::get('/contact', function (Request $request) use ($getCurrentUser, $getSellerAccess) {
    if (! session('authenticated')) {
        return redirect()->route('login')->with('error', 'Please log in or sign up to view Support.');
    }

    return view('contact', [
        'sellerActive' => $getSellerAccess($getCurrentUser())['active'],
    ]);
});

Route::get('/devsell/join', function (Request $request) use ($getCurrentUser, $getSellerApplication, $getSellerAccess) {
    if (! session('authenticated')) {
        return redirect()->route('login')->with('error', 'Please log in to apply for DevSell.');
    }

    $user = $getCurrentUser();
    $sellerAccess = $getSellerAccess($user);

    $wantsEdit = (bool) $request->boolean('edit');

    // If already active, normally go straight to seller tools,
    // but allow sellers to edit their DevSell info any time.
    if (($sellerAccess['active'] ?? false) === true && $wantsEdit === false) {
        return redirect()->route('seller.templates.index');
    }

    // If an approved seller is entering edit mode, mark it for admin visibility.
    if (($sellerAccess['active'] ?? false) === true && $wantsEdit === true && ! empty($user->devsell_active)) {
        $user->forceFill(['devsell_editing' => true])->save();
    }

    return view('devsell', [

        'application' => $getSellerApplication($user, null),
        'pendingVerification' => null,
        'sellerAccess' => $sellerAccess,
    ]);
})->name('devsell.join');

Route::post('/devsell/join', function (Request $request) use ($getCurrentUser, $submitDevSellForReview) {
    if (! session('authenticated')) {
        return redirect()->route('login')->with('error', 'Please log in to apply for DevSell.');
    }

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

    $application = [
        'display_name' => $data['display_name'],
        'email' => $user->email,
        'store_name' => $data['store_name'],
        'specialty' => $data['specialty'],
        'portfolio' => $data['portfolio'] ?? '',
        'bio' => $data['bio'],
    ];

    session([
        'authenticated' => true,
        'user_id' => $user->id,
    ]);

$submitDevSellForReview($user, $application, true);

    // If seller was already active and this submission came from edit mode,
    // keep access but mark "editing in progress" for admin visibility.
    $wasEditing = (bool) $request->boolean('edit');
    if ($wasEditing && ($user->devsell_active ?? false)) {
        $user->forceFill(['devsell_editing' => true])->save();
    }

    return redirect()->route('devsell.join')->with('success', 'Your DevSell request has been submitted for admin review.');

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
        'role' => ['nullable', 'string', 'max:80'],
        'location' => ['nullable', 'string', 'max:80'],
        'bio' => ['nullable', 'string', 'max:200'],
        'avatar_file' => ['nullable', 'image', 'max:2048'],
        'status' => ['nullable', Rule::in(array_keys($statusOptions))],
    ]);
    // Handle uploaded avatar file (preferred). If left empty, keep existing/default avatar.
    $avatarUrl = $user->profile_avatar ?? 'https://static.vecteezy.com/system/resources/previews/046/010/545/non_2x/user-icon-simple-design-free-vector.jpg';

    if ($request->hasFile('avatar_file') && $request->file('avatar_file')->isValid()) {
        $path = $request->file('avatar_file')->store('avatars', 'public');
        $avatarUrl = Storage::url($path);
    }

$user->forceFill([
        'name' => $profile['name'],
        'email' => $profile['email'],
        // If role/location/status left empty, keep existing values.
        'profile_role' => array_key_exists('role', $profile) ? $profile['role'] : $user->profile_role,
        'profile_location' => array_key_exists('location', $profile) ? $profile['location'] : $user->profile_location,
        'profile_bio' => $profile['bio'] ?? $user->profile_bio,
        'profile_avatar' => $avatarUrl,
        'profile_status' => array_key_exists('status', $profile) ? ($profile['status'] ?? $user->profile_status) : $user->profile_status,
    ])->save();

    return redirect('/account')->with('success', 'Profile updated successfully.');
});

Route::get('/purchases', [BuyerController::class, 'purchases'])->name('purchases');
Route::get('/purchases/{purchase}/download', [BuyerController::class, 'downloadPurchase'])->name('purchases.download');

Route::get('/sellers/{seller}', [BuyerController::class, 'seller'])->name('sellers.show');
Route::get('/templates/{listing}', [BuyerController::class, 'show'])->name('templates.show');

// Cart routes
Route::get('/cart', [BuyerController::class, 'cart'])->name('cart');
Route::get('/cart/checkout', [BuyerController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout', [BuyerController::class, 'processCheckout'])->name('cart.pay');
Route::post('/cart/add/{listing}', [BuyerController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{listing}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/templates/{listing}/buy', [BuyerController::class, 'buyNow'])->name('templates.buy');
Route::post('/templates/{listing}/like', [BuyerController::class, 'toggleLike'])->name('templates.like');
Route::post('/templates/{listing}/review', [BuyerController::class, 'storeReview'])->name('templates.review');

Route::prefix('seller')->name('seller.')->group(function () {
    Route::resource('templates', TemplateController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

Route::prefix('admin')->name('admin.')->middleware(AdminMiddleware::class)->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/templates/{listing}', [AdminController::class, 'show'])->name('templates.show');
    Route::post('/templates/{listing}/approve', [AdminController::class, 'approve'])->name('templates.approve');
    Route::post('/templates/{listing}/reject', [AdminController::class, 'reject'])->name('templates.reject');
    Route::post('/devsell/{user}/approve', [AdminController::class, 'approveSeller'])->name('devsell.approve');
    Route::post('/devsell/{user}/reject', [AdminController::class, 'rejectSeller'])->name('devsell.reject');
});
