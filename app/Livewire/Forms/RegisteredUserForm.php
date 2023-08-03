<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Livewire\Features\SupportRedirects\Redirector;

class RegisteredUserForm extends Form
{
    #[Rule(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Rule(['required', 'string', 'email', 'max:255', 'unique:App\Models\User,email'])]
    public string $email = '';

    #[Rule(['required', 'confirmed', 'min:8'])]
    public string $password = '';

    #[Rule(['required'])]
    public string $password_confirmation = '';

    public function save(): Redirector
    {
        $this->validate();

        $user = User::create(array_merge(
            $this->only(['name', 'email']),
            ['password' => Hash::make($this->password)]
        ));

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
