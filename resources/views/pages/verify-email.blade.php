<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    <div x-data="{ show: false }" x-on:email-verification-link-sent.window="show = !show" class="mb-4 text-sm font-medium text-green-600">
        <p x-show="show">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
    </div>

    <div class="flex items-center justify-between mt-4">
        <div>
            <livewire:email-verification-notification />

            <x-primary-button form="send-email-verification">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
