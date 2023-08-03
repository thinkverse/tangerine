<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('profile.update-information')
        ->set([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->call('update')
        ->assertHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('profile.update-information')
        ->set([
            'name' => 'Test User',
            'email' => $user->email,
        ])
        ->call('update')
        ->assertHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('profile.delete-account')
        ->set('password', 'password')
        ->call('delete')
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('profile.delete-account')
        ->set('password', 'wrong-password')
        ->call('delete')
        ->assertHasErrors('password')
        ->assertSee('The password is incorrect.');

    $this->assertAuthenticated();
    $this->assertNotNull($user->fresh());
});
