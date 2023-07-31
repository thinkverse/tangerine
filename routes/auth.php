<?php

use App\Http\Controllers\Auth\DestroyAuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('reset-password', NewPasswordController::class)
            ->middleware('guest')
            ->name('password.store');

Route::middleware('auth')->group(function () {
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', EmailVerificationNotificationController::class)
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::post('confirm-password', ConfirmablePasswordController::class)
                ->name('password.confirm');

    Route::post('logout', DestroyAuthenticatedSessionController::class)
                ->name('logout');
});
