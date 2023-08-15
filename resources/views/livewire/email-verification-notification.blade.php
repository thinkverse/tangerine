<?php

use function Livewire\Volt\action;

use App\Providers\RouteServiceProvider;

$submit = action(function () {
    if (request()->user()->hasVerifiedEmail()) {
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    request()->user()->sendEmailVerificationNotification();

    $this->dispatch('email-verification-link-sent');
})->renderless();

?>

<form wire:submit="submit" id="send-email-verification"></form>
