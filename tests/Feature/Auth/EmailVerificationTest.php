<?php

use App\Models\User;
use Livewire\Volt\FragmentAlias;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use App\Providers\RouteServiceProvider;

beforeEach(function () {
    $this->user = User::factory()
        ->unverified()
        ->create();

    $this->actingAs($this->user);
});

test('email verification screen can be rendered', function () {
    $this->get('/verify-email')
        ->assertOk()
        ->assertSeeLivewire(
            FragmentAlias::encode(
                componentName: 'send-email.form',
                path: resource_path(
                    path: 'views/livewire/email-verification-notification.blade.php'
                ),
            )
        );
});

test('email can be verified', function () {
    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        name: 'verification.verify',
        expiration: now()->addMinutes(60),
        parameters: [
            'id' => $this->user->id,
            'hash' => sha1($this->user->email)
        ]
    );

    $response = $this->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($this->user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
});

test('email is not verified with invalid hash', function () {
    $verificationUrl = URL::temporarySignedRoute(
        name: 'verification.verify',
        expiration: now()->addMinutes(60),
        parameters: [
            'id' => $this->user->id,
            'hash' => sha1('wrong-email')
        ]
    );

    $this->get($verificationUrl);

    expect($this->user->fresh()->hasVerifiedEmail())->toBeFalse();
});
