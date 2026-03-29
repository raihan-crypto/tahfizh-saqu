<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->role === 'wali_santri') {
            return redirect('/wali-santri');
        }

        // Redirect to the current panel's dashboard
        return redirect(Filament::getPanel()->getUrl());
    }
}
