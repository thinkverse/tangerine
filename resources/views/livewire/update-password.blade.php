<?php

use App\Livewire\Forms\UpdatePasswordForm;
use Illuminate\Support\Facades\Hash;

use function Livewire\Volt\form;

form(UpdatePasswordForm::class);

$update = fn() => $this->form->save();

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="update" class="mt-6 space-y-6">
        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" wire:model="form.current_password" name="current_password" type="password" class="block w-full mt-1" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" wire:model="form.password" name="password" type="password" class="block w-full mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" wire:model="form.password_confirmation" name="password_confirmation" type="password" class="block w-full mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
