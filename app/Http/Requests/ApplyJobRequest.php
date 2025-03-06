<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User must be authenticated to access this route
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resume_option' => 'required|string',
            'resume_file' => 'required_if:resume_option,new|file|mimes:pdf,doc,docx|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'resume_option.required' => 'Please select a resume option.',
            'resume_file.required_if' => 'Please upload a resume file.',
            'resume_file.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume_file.max' => 'Resume file size must not exceed 5MB.',
        ];
    }
}
