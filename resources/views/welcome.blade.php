<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Shaghalni</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Floating Background Shapes -->
    <div class="absolute inset-0 overflow-hidden">
        <div
            class="absolute top-[15%] left-[-10%] md:left-[-5%] w-[600px] h-[140px] rotate-12 gradient-bg bg-indigo-500/15 blur-2xl rounded-full">
        </div>
        <div
            class="absolute top-[70%] right-[-5%] md:right-[0%] w-[500px] h-[120px] -rotate-15 gradient-bg bg-rose-500/15 blur-2xl rounded-full">
        </div>
        <div
            class="absolute bottom-[5%] left-[5%] md:left-[10%] w-[300px] h-[80px] -rotate-8 gradient-bg bg-violet-500/15 blur-2xl rounded-full">
        </div>
        <div
            class="absolute top-[10%] right-[15%] md:right-[20%] w-[200px] h-[60px] rotate-20 gradient-bg bg-amber-500/15 blur-2xl rounded-full">
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 text-center" x-data="{ show: false }" x-init="setTimeout(() => show = true, 1000)">
        <div x-cloak x-show="show"
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6"
            x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100">
            <span class="text-sm text-white/60">Shaghalni</span>
        </div>

        <h1 x-show="show" class="text-4xl sm:text-6xl md:text-8xl font-bold mb-6 tracking-tight"
            x-transition:enter="transition ease-out duration-700 delay-300"
            x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <span class="bg-clip-text text-transparent bg-gradient-to-b from-white to-white/80">Find Your</span><br>
            <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-300 via-white/90 to-rose-300 italic font-serif">
                Dream Career
            </span>
        </h1>

        <p x-cloak x-show="show" class="text-lg text-white/40 mb-8 max-w-xl mx-auto"
            x-transition:enter="transition ease-out duration-700 delay-500"
            x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            Connect with top employers and discover exciting opportunities that match your skills and aspirations.
        </p>

        <div x-cloak x-show="show" class="flex justify-center space-x-4"
            x-transition:enter="transition ease-out duration-700 delay-700"
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

    <!-- Bottom Gradient -->
    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/80 pointer-events-none"></div>

</body>
</html>