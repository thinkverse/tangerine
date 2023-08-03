<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('confirm-password')
        ->set('password', 'password')
        ->call('submit')
        ->assertRedirect()
        ->assertHasNoErrors()
        ->assertSessionHas('auth.password_confirmed_at');
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('confirm-password')
        ->set('password', 'wrong-password')
        ->call('submit')
        ->assertHasErrors('password');
});
