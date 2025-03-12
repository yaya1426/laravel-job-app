@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500 rounded-lg p-4 mb-6">
                        <div class="text-green-400">{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Applications List -->
                <div class="space-y-6">
                    @forelse($applications as $application)
                        <div class="border border-white/10 rounded-lg p-6">
                            <div class="flex justify-between items-start">
                                <!-- Job Info -->
                                <div class="space-y-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white">
                                            {{ $application->jobVacancy->title }}
                                        </h3>
                                        <p class="text-lg text-white/70">{{ $application->jobVacancy->company->name }}</p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <span class="text-white/70">
                                            <i class="fas fa-map-marker-alt"></i> {{ $application->jobVacancy->location }}
                                        </span>
                                        <span class="text-white/70">
                                            <i class="fas fa-money-bill"></i> ${{ number_format($application->jobVacancy->salary, 2) }}
                                        </span>
                                        <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full">
                                            {{ $application->jobVacancy->type }}
                                        </span>
                                    </div>

                                    <!-- Resume Info -->
                                    <div class="flex items-center gap-2 text-white/70">
                                        <i class="fas fa-file-alt"></i>
                                        <span>Applied with: {{ $application->resume->filename }}</span>
                                        <a href="{{ Storage::disk('cloud')->url($application->resume->fileUri) }}" 
                                           target="_blank"
                                           class="text-blue-400 hover:underline ml-2">
                                            View Resume
                                        </a>
                                    </div>
                                </div>

                                <!-- Application Status -->
                                <div class="flex flex-col items-end">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-500',
                                            'reviewing' => 'bg-blue-500',
                                            'accepted' => 'bg-green-500',
                                            'rejected' => 'bg-red-500'
                                        ];
                                        $statusColor = $statusColors[$application->status] ?? 'bg-gray-500';
                                    @endphp
                                    <span class="px-3 py-1 {{ $statusColor }} text-white text-sm rounded-full">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    <span class="text-white/50 text-sm mt-2">
                                        Applied {{ $application->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            @if($application->aiGeneratedFeedback)
                                <div class="mt-4 p-4 bg-gray-900 rounded-lg">
                                    <h4 class="text-white font-semibold mb-2">AI Feedback</h4>
                                    <p class="text-white/70">{{ $application->aiGeneratedFeedback }}</p>
                                    @if($application->aiGeneratedScore)
                                        <div class="mt-2">
                                            <span class="text-white/70">Match Score: </span>
                                            <span class="text-blue-400">{{ $application->aiGeneratedScore }}%</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-white/70 text-lg mb-4">You haven't submitted any applications yet.</div>
                            <a href="{{ route('dashboard') }}" 
                               class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Browse Jobs
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($applications->hasPages())
                    <div class="mt-6">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>