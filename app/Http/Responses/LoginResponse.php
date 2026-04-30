<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();
        $panel = Filament::getPanel();

        if ($user->role === 'wali_santri') {
            return redirect('/app/wali-santri');
        }

        return redirect($panel->getUrl() . '/dashboard');
    }
}
