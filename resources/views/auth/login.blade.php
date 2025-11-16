<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
        <x-input-label for="email" :value="__('Email')" class="block text-sm font-bold text-gray-700" />
        <x-text-input id="email" class="block mt-1 w-full p-3 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700" />
        <x-text-input id="password" class="block mt-1 w-full p-3 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out" type="password" name="password" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>


    <div class="flex items-center justify-end mt-4">
        <x-primary-button class="ms-3 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Log in') }}
        </x-primary-button>
    </div>
</form>
</x-guest-layout>
