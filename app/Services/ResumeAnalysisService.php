<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class ResumeAnalysisService
{
    /**
     * Extract text content from a PDF file
     */
    private function extractTextFromPdf(string $fileUrl): string
    {
        try {
            // Create a temporary file to store the PDF
            $tempFile = tempnam(sys_get_temp_dir(), 'resume_');

            // If it's a full URL, get the path part
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            if (!$filePath) {
                throw new \Exception("Invalid file URL: {$fileUrl}");
            }

            // Extract the filename from the path
            $filename = basename($filePath);

            // Construct the storage path - assuming files are stored in the resumes directory
            $storagePath = "resumes/{$filename}";

            Log::debug("Attempting to read file from cloud storage: {$storagePath}");

            // Check if file exists in cloud storage
            if (!Storage::disk('cloud')->exists($storagePath)) {
                throw new \Exception("File does not exist in cloud storage: {$storagePath}");
            }

            // Download the file from cloud storage to temp location
            $pdfContent = Storage::disk('cloud')->get($storagePath);
            if (!$pdfContent) {
                throw new \Exception("Could not read PDF content from storage: {$storagePath}");
            }

            Log::debug("Successfully downloaded PDF content, size: " . strlen($pdfContent) . " bytes");

            file_put_contents($tempFile, $pdfContent);

            // Check if pdftotext is available in the system
            $pdftotext_paths = ['/opt/homebrew/bin/pdftotext', '/usr/bin/pdftotext', '/usr/local/bin/pdftotext'];
            $pdftotext_available = false;

            foreach ($pdftotext_paths as $path) {
                if (file_exists($path)) {
                    $pdftotext_available = true;
                    break;
                }
            }

            if (!$pdftotext_available) {
                throw new \Exception("pdftotext utility is not installed. For macOS, run: brew install poppler. For Linux, run: sudo apt-get install poppler-utils");
            }

            // Extract text from PDF
            $text = (new Pdf())
                ->setPdf($tempFile)
                ->text();

            // Clean up the temporary file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            if (empty($text)) {
                throw new \Exception("No text could be extracted from the PDF");
            }

            Log::debug("Successfully extracted text from PDF, length: " . strlen($text) . " characters");
            return $text;

        } catch (\Exception $e) {
            // Clean up temp file if it exists
            if (isset($tempFile) && file_exists($tempFile)) {
                unlink($tempFile);
            }

            Log::error('PDF text extraction failed: ' . $e->getMessage());
            Log::debug('File URL: ' . $fileUrl);
            if (isset($storagePath)) {
                Log::debug('Storage path: ' . $storagePath);
            }
            throw $e;
        }
    }

    public function extractResumeInformation($fileUrl)
    {
        Log::debug('Extracting resume information from: ' . $fileUrl);

        try {
            // First extract text from the PDF
            $resumeText = $this->extractTextFromPdf($fileUrl);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a precise resume parser. Extract information exactly as it appears in the resume without adding any interpretation or additional text. Only extract text that explicitly exists in the resume.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Parse the following resume content and extract the information as a JSON object with these exact keys: 'summary', 'skills', 'experience', 'education'. Only include text that appears verbatim in the resume. If a section is not found, return an empty string for that key. Here's the resume content:\n\n{$resumeText}"
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.1
            ]);

            Log::debug('OpenAI Response: ' . $response->choices[0]->message->content);

            $result = $response->choices[0]->message->content;
            $parsed = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('OpenAI Response JSON parsing failed. Response content: ' . $result);
                throw new \Exception('Failed to parse OpenAI response as JSON: ' . json_last_error_msg());
            }

            // Validate that all required keys exist
            $requiredKeys = ['summary', 'skills', 'experience', 'education'];
            $missingKeys = array_diff($requiredKeys, array_keys($parsed));

            if (!empty($missingKeys)) {
                Log::error('OpenAI Response missing required keys: ' . implode(', ', $missingKeys));
                throw new \Exception('OpenAI response missing required keys: ' . implode(', ', $missingKeys));
            }

            return [
                'summary' => $parsed['summary'],
                'skills' => $parsed['skills'],
                'experience' => $parsed['experience'],
                'education' => $parsed['education'],
            ];

        } catch (\Exception $e) {
            Log::error('Resume extraction failed: ' . $e->getMessage());
            return [
                'summary' => '',
                'skills' => '',
                'experience' => '',
                'education' => ''
            ];
        }
    }

    public function evaluateJobApplication($jobVacancy, $resumeInfo)
    {
        try {
            $jobDetails = json_encode([
                'title' => $jobVacancy->title,
                'description' => $jobVacancy->description,
                'location' => $jobVacancy->location,
                'type' => $jobVacancy->type,
                'salary' => $jobVacancy->salary,
            ]);

            $resumeDetails = json_encode($resumeInfo);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert HR professional and job recruiter. Your task is to evaluate the compatibility between a job vacancy and a candidate\'s resume. Provide a score from 0 to 100 and detailed feedback. You must respond ONLY with a valid JSON object containing exactly these keys: "aiGeneratedScore" (integer 0-100) and "aiGeneratedFeedback" (string with detailed analysis). Do not include any other text or explanation in your response.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please evaluate this job application. \n\nJOB DETAILS:\n{$jobDetails}\n\nCANDIDATE RESUME:\n{$resumeDetails}"
                    ]
                ],
                'response_format' => ['type' => 'json_object']
            ]);

            $result = $response->choices[0]->message->content;
            $parsed = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('OpenAI Response JSON parsing failed. Response content: ' . $result);
                throw new \Exception('Failed to parse OpenAI response as JSON: ' . json_last_error_msg());
            }

            // Validate that all required keys exist and types are correct
            if (
                !isset($parsed['aiGeneratedScore']) || !is_numeric($parsed['aiGeneratedScore']) ||
                !isset($parsed['aiGeneratedFeedback']) || !is_string($parsed['aiGeneratedFeedback'])
            ) {
                throw new \Exception('OpenAI response missing required keys or invalid types');
            }

            // Ensure score is between 0 and 100
            $score = max(0, min(100, (int) $parsed['aiGeneratedScore']));

            return [
                'aiGeneratedScore' => $score,
                'aiGeneratedFeedback' => $parsed['aiGeneratedFeedback']
            ];

        } catch (\Exception $e) {
            Log::error('Job application evaluation failed: ' . $e->getMessage());
            return [
                'aiGeneratedScore' => 0,
                'aiGeneratedFeedback' => 'Failed to evaluate application due to technical error.'
            ];
        }
    }
}
