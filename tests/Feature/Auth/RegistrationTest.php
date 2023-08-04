<?php

use Livewire\Volt\Volt;
use Livewire\Volt\FragmentAlias;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

test('registration screen can be rendered', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSeeLivewire(
            FragmentAlias::encode(
                componentName: 'register.form',
                path: resource_path(
                    path: 'views/pages/register.blade.php'
                ),
            )
        );
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
