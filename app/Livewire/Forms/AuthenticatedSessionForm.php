<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionForm extends Form
{
    #[Rule(['required', 'string', 'email'])]
    public string $email = '';

    #[Rule(['required', 'string'])]
    public string $password = '';

    #[Rule(['boolean'])]
    public bool $remember = false;

    public function authenticate()
    {
        $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            return $this->addError('email', trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]));
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            return $this->addError('email', __('auth.failed'));
        }

        RateLimiter::clear($this->throttleKey());

        session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
