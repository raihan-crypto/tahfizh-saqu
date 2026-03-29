<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->role === 'wali_santri') {
            return redirect('/wali-santri');
        }

        // admin panel is at /admin, app panel is at /
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/dashboard');
    }
}
