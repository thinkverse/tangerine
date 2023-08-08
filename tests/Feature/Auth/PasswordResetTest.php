<?php

use App\Models\User;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

test('reset password link screen can be rendered', function () {
    $this->assertGuest()
        ->get('/forgot-password')
        ->assertOk()
        ->assertSeeVolt('forgot-password.form');
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Volt::test('forgot-password')
        ->set('form.email', $user->email)
        ->call('submit')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    Volt::test('forgot-password')
        ->set('form.email', $user->email)
        ->call('submit')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $this->assertGuest()
            ->get(url(
                path: '/reset-password/',
                parameters: ['token' => $notification->token]
            ))
            ->assertOk()
            ->assertSeeVolt('reset-password.form');

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Volt::test('forgot-password')
        ->set('form.email', $user->email)
        ->call('submit')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        Volt::test('reset-password/[token]', [
            'token' => $notification->token
        ])
            ->set([
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->call('submit')
            ->assertSessionHasNoErrors();

        return true;
    });
});
