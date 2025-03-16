<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Http\Requests\ApplyJobRequest;
use App\Services\ResumeAnalysisService;
use Illuminate\Support\Str;

class JobVacancyController extends Controller
{
    protected $resumeAnalysisService;

    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }

    public function show($id)
    {
        $job = JobVacancy::with(['company', 'jobCategory'])->findOrFail($id);
        
        // Increment the view count
        $job->increment('view_count');
        
        return view('job-vacancy.show', compact('job'));
    }

    public function applyJob($id)
    {
        $job = JobVacancy::with(['company'])->findOrFail($id);
        return view('job-vacancy.apply', compact('job'));
    }

    public function storeJobApplication(ApplyJobRequest $request, $id)
    {
        $job = JobVacancy::findOrFail($id);
        $resumeId = null;
        $extractedInfo = null;

        // Handle resume selection/upload
        if ($request->resume_option === 'new') {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $filename = $file->getClientOriginalName();

            // Generate a unique filename with UUID
            $uniqueFilename = Str::uuid() . '.' . $extension;

            // Store file in Laravel Cloud Object Store
            $path = $file->storeAs('resumes', $uniqueFilename, 'cloud');

            // Get the full URL for the stored file
            $fileUrl = config('filesystems.disks.cloud.url') . '/' . $path;

            // Extract information from the file URL using the service
            $extractedInfo = $this->resumeAnalysisService->extractResumeInformation($fileUrl);

            // Create new resume record
            $resume = Resume::create([
                'filename' => $filename,
                'fileUri' => $path,
                'userId' => auth()->id(),
                'contactDetails' => json_encode([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email
                ]),
                'summary' => $extractedInfo['summary'],
                'skills' => $extractedInfo['skills'],
                'experience' => $extractedInfo['experience'],
                'education' => $extractedInfo['education'],
            ]);

            $resumeId = $resume->id;
        } else {
            // Get existing resume ID and its information
            $resumeId = str_replace('existing_', '', $request->resume_option);
            $resume = Resume::findOrFail($resumeId);
            $extractedInfo = [
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education,
            ];
        }

        // Evaluate the job application using AI
        $evaluation = $this->resumeAnalysisService->evaluateJobApplication($job, $extractedInfo);

        // Create job application with AI evaluation results
        JobApplication::create([
            'status' => 'pending',
            'jobId' => $job->id,
            'resumeId' => $resumeId,
            'userId' => auth()->id(),
            'aiGeneratedScore' => $evaluation['aiGeneratedScore'],
            'aiGeneratedFeedback' => $evaluation['aiGeneratedFeedback'],
        ]);

        return redirect()->route('job-applications.index')
            ->with('success', 'Your application has been submitted successfully!');
    }

    public function thankYou()
    {
        return view('job.thankYou');
    }
}
