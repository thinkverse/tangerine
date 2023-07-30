<?php

use App\Providers\RouteServiceProvider;
use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Volt::test('register')
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password')
        ->set('form.password_confirmation', 'password')
        ->call('submit')
        ->assertRedirect(RouteServiceProvider::HOME);

    $this->assertAuthenticated();
});
