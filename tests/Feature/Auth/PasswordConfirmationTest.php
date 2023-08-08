<?php

use App\Models\User;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('confirm password screen can be rendered', function () {
    $this->get('/confirm-password')
        ->assertOk()
        ->assertSeeVolt('confirm-password.form');
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
