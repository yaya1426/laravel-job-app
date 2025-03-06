<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationsController extends Controller
{
    public function index()
    {
        $applications = JobApplication::with(['jobVacancy.company', 'resume'])
            ->where('userId', auth()->id())
            ->latest()
            ->paginate(10);

        return view('job-applications.index', compact('applications'));
    }
}
