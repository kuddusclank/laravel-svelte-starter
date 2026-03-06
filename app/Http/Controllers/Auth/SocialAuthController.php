<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected array $providers = ['github', 'facebook', 'x', 'google', 'apple'];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, $this->providers), 404);

        return Socialite::driver($this->driver($provider))->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, $this->providers), 404);

        try {
            $socialUser = Socialite::driver($this->driver($provider))->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed. Please try again.');
        }

        if (! $socialUser->getEmail()) {
            return redirect('/login')->with('error', 'An email address is required to sign in.');
        }

        // Look up by provider + provider_id first (returning user via this provider)
        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (! $user) {
            $existing = User::where('email', $socialUser->getEmail())->first();

            if ($existing) {
                // Only link if the existing account has no other provider attached.
                // This prevents an attacker from hijacking an account by creating
                // a social account with the victim's email.
                if ($existing->provider) {
                    return redirect('/login')->with('error', 'This email is already linked to another sign-in method.');
                }

                $existing->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);

                $user = $existing;
            } else {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user, remember: true);

        return redirect()->intended('/dashboard');
    }

    protected function driver(string $provider): string
    {
        return $provider === 'x' ? 'twitter' : $provider;
    }
}
