<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function Livewire\Volt\{state, rules, mount};

state(['user' => auth()->user(), 'name' => '', 'email' => '']);

rules([
    'name' => ['string', 'max:255'],
    'email' => ['email', 'max:255', Rule::unique('users')->ignore(auth()->user()->id)],
]);

mount(function () {
    $this->email = $this->user->email;
    $this->name = $this->user->name;
});

$update = function () {
    $this->validate();

    request()->user()->fill($this->all());

    if (request()->user()->isDirty('email')) {
        request()->user()->email_verified_at = null;
    }

    request()->user()->save();

    return redirect('/profile')->with('status', 'profile-updated');
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <livewire:email-verification-notification />

    <form wire:submit="update" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full mt-1" wire:model="name" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="user.email" type="email" class="block w-full mt-1" wire:model="email" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800 ">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-email-verification" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    <div x-data="{ show: false }" x-on:email-verification-link-sent.window="show = !show" class="mt-2 text-sm font-medium text-green-600">
                        <p x-cloak x-show="show">{{ __('A new verification link has been sent to your email address.') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 "
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
