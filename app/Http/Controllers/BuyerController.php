<?php

namespace App\Http\Controllers;

use App\Models\TemplateListing;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $query = TemplateListing::where('status', 'active');

        // Basic filters from query params
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

        // Search by title or tags
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tags', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        // Rating stub: add if avg_rating field added later
        // if ($request->filled('min_rating')) { ... }

        $listings = $query->with('user')->latest()->paginate(12);

        // Categories for filter dropdown
        $categories = TemplateListing::where('status', 'active')->distinct()->pluck('category');

        // Price ranges suggestion (dynamic min/max)
        $minPrice = TemplateListing::where('status', 'active')->min('price');
        $maxPrice = TemplateListing::where('status', 'active')->max('price');

        return view('search', compact('listings', 'categories', 'minPrice', 'maxPrice'));
    }

    public function show(TemplateListing $listing)
    {
        if ($listing->status !== 'active') {
            abort(404);
        }

        $listing->load('user');

        return view('market.templates.show', compact('listing'));
    }
}

