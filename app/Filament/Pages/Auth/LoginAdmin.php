<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class LoginAdmin extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return new \Illuminate\Support\HtmlString(
            '<div style="text-align: center;">' .
                '<div style="width: 3.5rem; height: 3.5rem; margin: 0 auto 1rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #ef4444, #8b5cf6); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 25px rgba(245,158,11,0.3);">' .
                    '<svg style="width: 1.75rem; height: 1.75rem; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>' .
                '</div>' .
                '<span style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Masuk ke Sistem</span>' .
            '</div>'
        );
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new \Illuminate\Support\HtmlString(
            '<p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.25rem;">SD Tahfizh SaQu UMA — Sistem Manajemen Tahfidz</p>'
        );
    }
}
