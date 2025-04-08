<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Job Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dark Card Container -->
            <div class="bg-black shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-white mb-4">
                    Welcome, {{ Auth::user()->name }}! 👋
                </h3>

                <!-- Search & Filters -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <!-- Search Bar -->
                    <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-1/3">
                        <input type="text"
                            name="search"
                            placeholder="Search jobs..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500">
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                    </form>

                    <!-- Job Type Filters -->
                    <div class="flex space-x-2">
                        <a href="{{ route('dashboard', ['filter' => 'full-time']) }}"
                            class="px-4 py-2 {{ $activeFilter === 'full-time' ? 'bg-white text-black' : 'bg-black text-white' }} border border-white rounded-md text-sm hover:bg-white hover:text-black transition">
                            Full Time
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'remote']) }}"
                            class="px-4 py-2 {{ $activeFilter === 'remote' ? 'bg-white text-black' : 'bg-black text-white' }} border border-white rounded-md text-sm hover:bg-white hover:text-black transition">
                            Remote
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'hybrid']) }}"
                            class="px-4 py-2 {{ $activeFilter === 'hybrid' ? 'bg-white text-black' : 'bg-black text-white' }} border border-white rounded-md text-sm hover:bg-white hover:text-black transition">
                            Hybrid
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'contract']) }}"
                            class="px-4 py-2 {{ $activeFilter === 'contract' ? 'bg-white text-black' : 'bg-black text-white' }} border border-white rounded-md text-sm hover:bg-white hover:text-black transition">
                            Contract
                        </a>
                        @if($activeFilter)
                            <a href="{{ route('dashboard') }}"
                                class="px-4 py-2 bg-red-600 text-white border border-red-600 rounded-md text-sm hover:bg-red-700 transition">
                                Clear Filter
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Job Listings -->
                <div class="space-y-6">
                    @foreach($jobs as $job)
                        <div class="border-b border-white/10 pb-4">
                            <div class="flex justify-between items-start">
                                <!-- Job Info -->
                                <div>
                                    <a href="{{ route('job-vacancy.show', $job->id) }}"
                                        class="text-lg font-semibold text-blue-400 hover:underline">
                                        {{ $job->title }}
                                    </a>
                                    <p class="text-sm text-white">
                                        {{ $job->company->name }} - {{ $job->location }}
                                    </p>
                                    <div class="text-xs text-white/70 mt-1">
                                        {{ $job->experience }} Yrs of Exp • {{ $job->skills }}
                                    </div>
                                    <div class="text-xs text-white/70 mt-1">
                                        {{ '$' . number_format($job->salary, 2) }}
                                    </div>
                                </div>

                                <!-- Job Type Tag -->
                                <div class="flex space-x-2">
                                    <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded-md">
                                        {{ $job->type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
