<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Apply for Job') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black shadow-sm sm:rounded-lg p-6">
                <!-- Back Button -->
                <a href="{{ route('job-vacancy.show', $job->id) }}" class="text-blue-400 hover:underline mb-6 inline-block">
                    ← Back to Job Details
                </a>

                <!-- Job Summary -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-white mb-2">{{ $job->title }}</h1>
                    <div class="flex flex-col gap-4">
                        <!-- Company and Basic Info -->
                        <div>
                            <p class="text-lg text-white">{{ $job->company->name }}</p>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-white/70">
                                    <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                                </span>
                                <span class="text-white/70">
                                    <i class="fas fa-money-bill"></i> ${{ number_format($job->salary, 2) }}
                                </span>
                                <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full">
                                    {{ $job->type }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Form -->
                <form action="{{ route('job.store-application', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500 rounded-lg p-4">
                            <div class="font-medium text-red-400">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Resume Selection -->
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>
                        
                        <!-- Existing Resumes -->
                        <div class="mb-6">
                            <label class="block text-white mb-2">Select from your existing resumes:</label>
                            <div class="space-y-4">
                                @forelse(auth()->user()->resumes as $resume)
                                    <div class="flex items-center">
                                        <input type="radio" 
                                               name="resume_option" 
                                               value="existing_{{ $resume->id }}" 
                                               id="resume_{{ $resume->id }}"
                                               @error('resume_option') class="mr-3 border-red-500" @else class="mr-3" @enderror>
                                        <label for="resume_{{ $resume->id }}" class="text-white">
                                            {{ $resume->filename }} 
                                            <span class="text-gray-400 text-sm">(Last updated: {{ $resume->updated_at->format('M d, Y') }})</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-gray-400">No existing resumes found.</p>
                                @endforelse
                            </div>
                            @error('resume_option')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload New Resume -->
                        <div x-data="{ fileName: '' }">
                            <label class="block text-white mb-2">Or upload a new resume:</label>
                            <div class="flex items-center">
                                <input type="radio" 
                                       x-ref="newResumeRadio"
                                       name="resume_option" 
                                       value="new" 
                                       id="new_resume"
                                       :checked="fileName !== ''"
                                       @error('resume_option') class="mr-3 border-red-500" @else class="mr-3" @enderror>
                                <div class="flex-1">
                                    <label for="new_resume_file" 
                                           class="block text-white cursor-pointer">
                                        <div class="border-2 border-dashed border-gray-600 rounded-lg p-4 hover:border-blue-500 transition"
                                             :class="{ 'border-blue-500': fileName, 'border-red-500': {{ $errors->has('resume_file') ? 'true' : 'false' }} }">
                                            <input type="file" 
                                                   name="resume_file" 
                                                   id="new_resume_file" 
                                                   accept=".pdf,.doc,.docx"
                                                   class="hidden"
                                                   @change="fileName = $event.target.files[0].name; $refs.newResumeRadio.checked = true">
                                            <div class="text-center">
                                                <template x-if="!fileName">
                                                    <p class="text-gray-400">Click to upload PDF, DOC, or DOCX (Max 5MB)</p>
                                                </template>
                                                <template x-if="fileName">
                                                    <div>
                                                        <p class="text-blue-400" x-text="fileName"></p>
                                                        <p class="text-gray-400 text-sm mt-1">Click to change file</p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </label>
                                    @error('resume_file')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 