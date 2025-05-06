<x-main-layout title="Shaghalni - Find Your Dream Career">
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
        <div x-cloak x-show="show"
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6"
            x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100">
            <span class="text-sm text-white/60">Shaghalni</span>
        </div>

        <h1 x-show="show" class="text-4xl sm:text-6xl md:text-8xl font-bold mb-6 tracking-tight"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <span class="bg-clip-text text-transparent bg-gradient-to-b from-white to-white/80">Find Your</span><br>
            <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-300 via-white/90 to-rose-300 italic font-serif">
                Dream Career
            </span>
        </h1>

        <p x-cloak x-show="show" class="text-lg text-white/40 mb-8 max-w-xl mx-auto"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            Connect with top employers and discover exciting opportunities that match your skills and aspirations.
        </p>

        <div x-cloak x-show="show" class="flex justify-center space-x-4"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <a href="{{ route('register') }}"
                class="px-6 py-3 rounded-lg bg-white/10 text-white border border-white/20 hover:bg-white/20 transition">
                Register
            </a>
            <a href="{{ route('login') }}"
                class="px-6 py-3 rounded-lg bg-gradient-to-r from-indigo-500 to-rose-500 text-white hover:from-indigo-600 hover:to-rose-600 transition">
                Login
            </a>
        </div>
    </div>
</x-main-layout>