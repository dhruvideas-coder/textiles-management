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
                // Not invited, reject
                return redirect('/admin/login')->withErrors(['email' => 'Your email is not invited to this system.']);
            }
            
            // Update provider info
            $user->update([
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'name' => $user->name === '...' ? $googleUser->getName() : $user->name, // Keep existing name if already set
            ]);
            
            Auth::login($user);
            
            return redirect('/admin');
            
        } catch (\Exception $e) {
            return redirect('/admin/login')->withErrors(['email' => 'Failed to authenticate with Google.']);
        }
    }
}
