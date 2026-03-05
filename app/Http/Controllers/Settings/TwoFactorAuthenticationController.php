<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorAuthenticationController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('settings/TwoFactor', [
            'twoFactorEnabled' => ! is_null($request->user()->two_factor_secret),
            'twoFactorConfirmed' => ! is_null($request->user()->two_factor_confirmed_at),
        ]);
    }
}
