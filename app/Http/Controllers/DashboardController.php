<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');

        if ($searchTerm) {
            $jobs = JobVacancy::search($searchTerm)
                ->query(function ($query) use ($request) {
                    $query->with('company')
                          ->whereDoesntHave('jobApplications', function ($q) {
                              $q->where('userId', auth()->id());
                          });

                    if ($request->has('filter') && in_array($request->filter, ['full-time', 'remote', 'hybrid', 'contract'])) {
                        $query->where('type', $request->filter);
                    }
                })
                ->paginate(10)
                ->withQueryString();
        } else {
            $query = JobVacancy::with('company')
                ->whereDoesntHave('jobApplications', function ($query) {
                    $query->where('userId', auth()->id());
                });

            if ($request->has('filter') && in_array($request->filter, ['full-time', 'remote', 'hybrid', 'contract'])) {
                $query->where('type', $request->filter);
            }

            $jobs = $query->latest()->paginate(10)->withQueryString();
        }

        $activeFilter = $request->filter;

        return view('dashboard', compact('jobs', 'activeFilter'));
    }
}
