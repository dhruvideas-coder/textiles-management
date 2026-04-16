<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;

class Login extends \Filament\Auth\Pages\Login
{
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
}
