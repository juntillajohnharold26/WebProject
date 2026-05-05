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
        ];

        return view('admin.index', compact('listings', 'counts', 'status'));
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
}
