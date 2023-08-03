<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Volt\Volt;

test('reset password link screen can be rendered', function () {
    $this->get('/forgot-password')->assertStatus(200);
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
        $this->get('/reset-password/'.$notification->token)->assertStatus(200);

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
