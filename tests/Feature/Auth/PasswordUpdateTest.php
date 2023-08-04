<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('password can be updated', function () {
    Volt::test('profile.update-password')
        ->set('form', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('update')
        ->assertHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertTrue(Hash::check('new-password', $this->user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    Volt::test('profile.update-password')
        ->set('form', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('update')
        ->assertHasErrors([
            'form.current_password' => ['current_password']
        ]);
});
