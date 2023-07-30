<?php

use App\Livewire\Forms\ForgotPasswordForm;

use function Livewire\Volt\form;
use function Laravel\Folio\middleware;

middleware(['guest']);

form(ForgotPasswordForm::class);

$submit = function () {
    $this->form->save();
}

?>

<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @volt
    <form wire:submit="submit">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" wire:model="form.email" required autofocus />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
    @endvolt
</x-guest-layout>
