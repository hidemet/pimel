<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // VECCHIA
            // return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
            // NUOVA
            if ($request->user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
            }
            return redirect()->intended(route('profile.edit', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // VECCHIA
        // return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        // NUOVA
        if ($request->user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
        }
        return redirect()->intended(route('profile.edit', absolute: false).'?verified=1');
    }
}