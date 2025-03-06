<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Job Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black shadow-sm sm:rounded-lg p-6">
                <!-- Back Button -->
                <a href="{{ route('dashboard') }}" class="text-blue-400 hover:underline mb-6 inline-block">
                    ← Back to Jobs
                </a>

                <!-- Job Header -->
                <div class="border-b border-white/10 pb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-white mb-2">{{ $job->title }}</h1>
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
                        
                        <!-- Apply Button -->
                        <div>
                            <a href="{{ route('job.apply', $job->id) }}" 
                               class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                    <!-- Main Content -->
                    <div class="md:col-span-2">
                        <!-- Job Description -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-white mb-4">Job Description</h2>
                            <div class="text-white/90 space-y-4">
                                {!! nl2br(e($job->description)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-900 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Job Overview</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white/70">Posted Date</p>
                                    <p class="text-white">{{ $job->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-white/70">Location</p>
                                    <p class="text-white">{{ $job->location }}</p>
                                </div>
                                <div>
                                    <p class="text-white/70">Job Type</p>
                                    <p class="text-white">{{ $job->type }}</p>
                                </div>
                                <div>
                                    <p class="text-white/70">Salary</p>
                                    <p class="text-white">${{ number_format($job->salary, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-white/70">Category</p>
                                    <p class="text-white">{{ $job->jobCategory->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
