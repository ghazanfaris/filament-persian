<?php

namespace Sghazanfari\FilamentPersian\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Sghazanfari\FilamentPersian\Settings\FontManager;

class Login extends BaseLogin
{
    public function getLayout(): string
    {
        $template = FontManager::loginTemplate();
        $view = "filament-persian::auth.layouts.{$template}";

        // اگر قالب پیدا نشد، از پیش‌فرض Filament استفاده کن
        if (! view()->exists($view)) {
            return parent::getLayout();
        }

        return $view;
    }
}