<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-white/60">Sign in to your account to continue</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white/80" />
            <x-text-input id="email"
                class="block mt-1 w-full bg-white/5 text-white border-white/10 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-white/80" />

            <x-text-input id="password"
                class="block mt-1 w-full bg-white/5 text-white border-white/10 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-white/10 bg-white/5 text-indigo-500 shadow-sm focus:ring-indigo-500"
                    name="remember">
                <span class="ms-2 text-sm text-white/80">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-white/60 hover:text-white transition"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <x-primary-button class="w-full justify-center bg-gradient-to-r from-indigo-500 to-rose-500 hover:from-indigo-600 hover:to-rose-600 text-white px-4 py-2 rounded-lg transition">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-white/60">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 transition">
                    {{ __('Sign up') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>