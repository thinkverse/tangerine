<?php

use App\Models\User;
use Livewire\Volt\Volt;
use Livewire\Volt\FragmentAlias;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('confirm password screen can be rendered', function () {
    $this->get('/confirm-password')
        ->assertOk()
        ->assertSeeLivewire(
            FragmentAlias::encode(
                componentName: 'confirm-password.form',
                path: resource_path(
                    path: 'views/pages/confirm-password.blade.php'
                ),
            )
        );
});

test('password can be confirmed', function () {
    Volt::test('confirm-password')
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect()
        ->assertHasNoErrors()
        ->assertSessionHas('auth.password_confirmed_at');
});

test('password is not confirmed with invalid password', function () {
    Volt::test('confirm-password')
        ->set('password', 'wrong-password')
        ->call('submit')
        ->assertHasErrors('password');
});
