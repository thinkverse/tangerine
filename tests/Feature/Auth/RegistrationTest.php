<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

test('registration screen can be rendered', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSeeVolt('register.form');
});

test('new users can register', function () {
    Event::fake();

    Volt::test('register')
        ->set('form', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('submit')
        ->assertRedirect(RouteServiceProvider::HOME);

    Event::assertDispatched(Registered::class);

    $this->assertAuthenticated();
});
