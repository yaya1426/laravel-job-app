<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Create Account</h2>
        <p class="text-white/60">Join us and start your journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white/80" />
            <x-text-input id="name"
                class="block mt-1 w-full bg-white/5 text-white border-white/10 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-white/80" />
            <x-text-input id="email"
                class="block mt-1 w-full bg-white/5 text-white border-white/10 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-white/80" />
            <x-text-input id="password"
                class="block mt-1 w-full bg-white/5 text-white border-white/10 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white/80" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full bg-white/5 text-white border-white/10 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <!-- Register Button -->
        <div class="mt-6">
            <x-primary-button class="w-full justify-center bg-gradient-to-r from-indigo-500 to-rose-500 hover:from-indigo-600 hover:to-rose-600 text-white px-4 py-2 rounded-lg transition">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-white/60">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 transition">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>