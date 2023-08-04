<?php

use App\Models\User;
use Livewire\Volt\Volt;
use Livewire\Volt\FragmentAlias;
use App\Providers\RouteServiceProvider;

test('login screen can be rendered', function () {
    $this->assertGuest()
        ->get('/login')
        ->assertOk()
        ->assertSeeLivewire(
            FragmentAlias::encode(
                componentName: 'login.form',
                path: resource_path(
                    path: 'views/pages/login.blade.php'
                ),
            )
        );
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
