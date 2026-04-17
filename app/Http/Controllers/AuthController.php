<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user is pre-approved (exists in DB)
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                return redirect(filament()->getLoginUrl() . '?google_error=' . urlencode('This email is not registered in the system. Please contact the administrator.'));
            }

            if ($user->login_try >= 5 && $user->blocked_until && now()->lessThan($user->blocked_until)) {
                return redirect(filament()->getLoginUrl() . '?google_error=' . urlencode('Your account has been blocked for 2 hours due to multiple failed login attempts.'));
            } elseif ($user->login_try >= 5 && $user->blocked_until && now()->greaterThanOrEqualTo($user->blocked_until)) {
                $user->update(['login_try' => 0, 'blocked_until' => null]);
            }
            
            // Update provider info
            $user->update([
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'name' => $user->name === '...' ? $googleUser->getName() : $user->name, // Keep existing name if already set
                'login_try' => 0,
                'blocked_until' => null,
            ]);
            
            Auth::login($user);
            
            return redirect('/');
            
        } catch (\Exception $e) {
            return redirect(filament()->getLoginUrl() . '?google_error=' . urlencode('Failed to authenticate with Google. Please try again.'));
        }
    }
}
