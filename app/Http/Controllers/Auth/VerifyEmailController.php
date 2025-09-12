<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller {
    public function __invoke(EmailVerificationRequest $request): RedirectResponse {

        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false) . '?verified=1');
            }
            return redirect()->intended(route('profile.edit', absolute: false) . '?verified=1');
        }

        // Se l'utente sta verificando l'email ora, contrassegnala come verificata.
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Dopo averla contrassegnata, reindirizzalo alla destinazione corretta.
        if ($request->user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false) . '?verified=1');
        }

        return redirect()->intended(route('profile.edit', absolute: false) . '?verified=1');
    }
}
