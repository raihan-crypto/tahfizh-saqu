<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;

class ApplyUserTheme
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->theme_color) {
            $warna = match (auth()->user()->theme_color) {
                'Blue'   => Color::Blue,
                'Green'  => Color::Green,
                'Purple' => Color::Purple,
                'Rose'   => Color::Rose,
                'Slate'  => Color::Slate,
                default  => Color::Amber,
            };
            
            FilamentColor::register([
                'primary' => $warna,
            ]);
        }

        return $next($request);
    }
}
