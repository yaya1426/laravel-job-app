<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Http\Requests\ApplyJobRequest;
use Illuminate\Support\Facades\Storage;

class JobVacancyController extends Controller
{
    public function show($id) {
        $job = JobVacancy::with(['company', 'jobCategory'])->findOrFail($id);
        return view('job-vacancy.show', compact('job'));
    }

    public function applyJob($id) {
        $job = JobVacancy::with(['company'])->findOrFail($id);
        return view('job-vacancy.apply', compact('job'));
    }

    public function storeJobApplication(ApplyJobRequest $request, $id) {
        $job = JobVacancy::findOrFail($id);
        $resumeId = null;

        // Handle resume selection/upload
        if ($request->resume_option === 'new') {
            $file = $request->file('resume_file');
            $filename = $file->getClientOriginalName();
            $path = $file->store('resumes', 'public');
            
            // Create new resume record
            $resume = Resume::create([
                'filename' => $filename,
                'fileUri' => $path,
                'userId' => auth()->id(),
                'contactDetails' => json_encode([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email
                ]),
                'summary' => '', // Can be updated later
                'skills' => '', // Can be updated later
                'experience' => '', // Can be updated later
                'education' => '', // Can be updated later
            ]);
            
            $resumeId = $resume->id;
        } else {
            // Get existing resume ID
            $resumeId = str_replace('existing_', '', $request->resume_option);
        }

        // Create job application
        JobApplication::create([
            'status' => 'pending',
            'jobId' => $job->id,
            'resumeId' => $resumeId,
            'userId' => auth()->id(),
        ]);

        return redirect()->route('job-applications.index')
            ->with('success', 'Your application has been submitted successfully!');
    }

    public function thankYou() {
        return view('job.thankYou');
    }
}
