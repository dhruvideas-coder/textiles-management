<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;

class Login extends \Filament\Auth\Pages\Login
{
    protected string $view = 'filament.pages.auth.login';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE),
                Html::make(fn () => view('components.google-login-button')->render()),
                $this->getFormContentComponent(),
                $this->getMultiFactorChallengeFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER),
            ]);
    }

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        $data = $this->form->getState();
        $user = \App\Models\User::where('email', $data['email'])->first();

        if ($user) {
            if ($user->login_try >= 5 && $user->blocked_until && now()->lessThan($user->blocked_until)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'data.email' => __('Your account has been blocked for 2 hours due to multiple failed login attempts.'),
                ]);
            } elseif ($user->login_try >= 5 && $user->blocked_until && now()->greaterThanOrEqualTo($user->blocked_until)) {
                $user->update(['login_try' => 0, 'blocked_until' => null]);
            }
        }

        try {
            $response = parent::authenticate();
            
            if ($user) {
                $user->update(['login_try' => 0, 'blocked_until' => null]);
            }
            
            return $response;
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($user) {
                $user->increment('login_try');
                if ($user->login_try >= 5) {
                    $user->update(['blocked_until' => now()->addHours(2)]);
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'data.email' => __('Your account has been blocked for 2 hours due to multiple failed login attempts.'),
                    ]);
                }
            }
            throw $e;
        }
    }
}
