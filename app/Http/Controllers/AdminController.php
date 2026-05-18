<?php

namespace App\Http\Controllers;

use App\Models\TemplateListing;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $query = TemplateListing::with('user')->orderBy('created_at', 'desc');

        if (in_array($status, ['pending', 'active', 'draft', 'archived'])) {
            $query->where('status', $status);
        }

        $listings = $query->paginate(12);

        $counts = [
            'pending' => TemplateListing::pending()->count(),
            'active' => TemplateListing::active()->count(),
            'draft' => TemplateListing::draft()->count(),
            'archived' => TemplateListing::archived()->count(),
            'all' => TemplateListing::count(),
            'pending_sellers' => User::where('devsell_status', 'pending')->count(),
        ];

        $pendingSellers = User::where('devsell_status', 'pending')
            ->latest()
            ->paginate(6, ['*'], 'sellers_page');

        return view('admin.index', compact('listings', 'counts', 'status', 'pendingSellers'));
    }

    public function show(TemplateListing $listing)
    {
        $listing->load('user');

        return view('admin.templates.show', compact('listing'));
    }

    public function approve(TemplateListing $listing)
    {
        if (! $listing->isPending()) {
            return redirect()->route('admin.index')->with('error', 'Only pending templates can be approved.');
        }

        $listing->update(['status' => 'active']);

        return redirect()->route('admin.index')->with('success', "Template \"{$listing->title}\" has been approved and is now live.");
    }

    public function reject(TemplateListing $listing)
    {
        if (! $listing->isPending()) {
            return redirect()->route('admin.index')->with('error', 'Only pending templates can be rejected.');
        }

        $listing->update(['status' => 'archived']);

        return redirect()->route('admin.index')->with('success', "Template \"{$listing->title}\" has been rejected.");
    }

    public function approveSeller(User $user, Request $request)
    {
        // Admin can also use this button to clear editing.
        if ($request->boolean('clear_editing')) {
            $user->forceFill(['devsell_editing' => false])->save();

            return redirect()->route('admin.index')->with('success', "{$user->devsell_display_name} editing flag cleared.");
        }

        if ($user->devsell_status !== 'pending') {
            return redirect()->route('admin.index')->with('error', 'Only pending DevSell requests can be approved.');
        }

        $user->forceFill([
            'devsell_active' => true,
            'devsell_status' => 'approved',
            'devsell_joined_at' => $user->devsell_joined_at ?? now(),
            'devsell_editing' => false,
        ])->save();

        return redirect()->route('admin.index')->with('success', "{$user->devsell_display_name} can now access DevSell.");
    }


    public function rejectSeller(User $user)
    {
        if ($user->devsell_status !== 'pending') {
            return redirect()->route('admin.index')->with('error', 'Only pending DevSell requests can be rejected.');
        }

        $user->forceFill([
            'devsell_active' => false,
            'devsell_status' => 'rejected',
            'devsell_editing' => false,
        ])->save();


        return redirect()->route('admin.index')->with('success', "{$user->devsell_display_name}'s DevSell request was rejected.");
    }
}
