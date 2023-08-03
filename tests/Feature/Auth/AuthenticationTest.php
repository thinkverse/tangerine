<?php

use App\Models\User;
use Livewire\Volt\Volt;
use App\Providers\RouteServiceProvider;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    Volt::test('login')
        ->set('form', [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('submit')
        ->assertRedirect(RouteServiceProvider::HOME);

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    Volt::test('login')
        ->set('form', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->call('submit')
        ->assertHasErrors('form.email');

    $this->assertGuest();
});
