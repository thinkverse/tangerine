<?php

use App\Models\User;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('profile page is displayed', function () {
    $this->get('/profile')
        ->assertOk()
        ->assertSeeVolt('profile.update-information')
        ->assertSeeVolt('profile.update-password')
        ->assertSeeVolt('profile.delete-account');
});

test('profile information can be updated', function () {
    Volt::test('profile.update-information')
        ->set([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->call('update')
        ->assertHasNoErrors()
        ->assertRedirect('/profile');

    $this->user->refresh();

    $this->assertSame('Test User', $this->user->name);
    $this->assertSame('test@example.com', $this->user->email);
    $this->assertNull($this->user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    Volt::test('profile.update-information')
        ->set([
            'name' => 'Test User',
            'email' => $this->user->email,
        ])
        ->call('update')
        ->assertHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($this->user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    Volt::test('profile.delete-account')
        ->set('password', 'password')
        ->call('delete')
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($this->user->fresh());
});

test('correct password must be provided to delete account', function () {
    Volt::test('profile.delete-account')
        ->set('password', 'wrong-password')
        ->call('delete')
        ->assertHasErrors([
            'password' => ['current_password'],
        ])
        ->assertSee('The password is incorrect.');

    $this->assertAuthenticated()
        ->assertNotNull($this->user->fresh());
});
