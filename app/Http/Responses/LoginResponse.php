<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        return match($user->role) {
            'admin'       => redirect('/admin/dashboard'),
            'wali_santri' => redirect('/app/wali-santri'),
            default       => redirect('/app/dashboard'),
        };
    }
}
