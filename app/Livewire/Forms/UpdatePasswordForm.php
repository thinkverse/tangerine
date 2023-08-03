<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportRedirects\Redirector;

class UpdatePasswordForm extends Form
{
    #[Rule(['required', 'current_password'])]
    public string $current_password = '';

    #[Rule(['required', 'min:8', 'confirmed'])]
    public string $password = '';

    #[Rule(['required_with:password'])]
    public string $password_confirmation = '';

    public function save(): Redirector
    {
        $this->validate();

        request()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        return redirect('/profile')
            ->with('status', 'password-updated');
    }
}
