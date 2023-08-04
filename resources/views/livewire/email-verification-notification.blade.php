<?php

use App\Providers\RouteServiceProvider;

$submit = function () {
    if (request()->user()->hasVerifiedEmail()) {
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    request()->user()->sendEmailVerificationNotification();

    $this->dispatch('email-verification-link-sent');
};

?>

@volt('send-email.form')
<form wire:submit="submit" id="send-email-verification"></form>
@endvolt
