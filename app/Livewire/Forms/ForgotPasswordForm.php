<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ForgotPasswordForm extends Form
{
    #[Rule('required|email')]
    public string $email = '';

    public function save()
    {
        $status = Password::sendResetLink(
            $this->validate(),
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect('/forgot-password')
                ->with('status', __($status))
            : $this->addError('email', __($status));
    }
}
