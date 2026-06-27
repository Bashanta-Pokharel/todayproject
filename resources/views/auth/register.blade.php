<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if($willCreateAdmin ?? false)
        <div class="mb-4 rounded-md border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-900">
            Create the first admin account for this store.
            @unless($adminBootstrapUser ?? false)
                <div class="mt-1 text-xs text-indigo-800">
                    Already have an account? Log in first, then open this page again to make that account admin.
                </div>
            @endunless
        </div>
    @endif

    @if($adminBootstrapUser ?? false)
        <form method="POST" action="{{ $registrationRoute ?? route('admin.register.store') }}">
            @csrf

            <div class="rounded-md border border-gray-200 bg-white px-4 py-4 text-sm text-gray-700">
                <div class="font-semibold text-gray-900">Use this account as admin</div>
                <div class="mt-1">{{ $adminBootstrapUser->name }} · {{ $adminBootstrapUser->email }}</div>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <x-primary-button>
                    {{ __('Create Admin') }}
                </x-primary-button>
            </div>
        </form>
    @else
    <form method="POST" action="{{ $registrationRoute ?? route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ ($willCreateAdmin ?? false) ? __('Create Admin') : __('Register') }}
            </x-primary-button>
        </div>
    </form>
    @endif
</x-guest-layout>
