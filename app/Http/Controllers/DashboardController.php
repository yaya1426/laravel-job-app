<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobVacancy::with('company')
            ->whereDoesntHave('jobApplications', function($query) {
                $query->where('userId', auth()->id());
            });
        
        // Apply job type filter
        if ($request->has('filter') && in_array($request->filter, ['full-time', 'part-time', 'remote'])) {
            $query->where('type', $request->filter);
        }

        // Apply search filter if search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Search in job vacancies table
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('company', function($query) use ($searchTerm) {
                      $query->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();
        $activeFilter = $request->filter;

        return view('dashboard', compact('jobs', 'activeFilter'));
    }
}
