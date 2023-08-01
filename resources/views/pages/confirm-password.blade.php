<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\state;
use function Laravel\Folio\middleware;

middleware(['auth']);

state(['password' => '']);

$submit = function() {
    if (! Auth::guard('web')->validate([
        'email' => request()->user()->email,
        'password' => $this->password,
    ])) {
        return $this->addError('password', __('auth.password'));
    }

    session()->put('auth.password_confirmed_at', time());

    return redirect()->intended(RouteServiceProvider::HOME);
};

?>

<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    @volt
    <form wire:submit="submit">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block w-full mt-1"
                            type="password"
                            name="password"
                            wire:model="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
    @endvolt
</x-guest-layout>
