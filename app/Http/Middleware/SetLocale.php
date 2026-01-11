<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // Default to config if session not set (optional, essentially redundant but explicit)
            app()->setLocale(config('app.locale'));
        }

        // Also set Carbon locale for date translations
        \Carbon\Carbon::setLocale(app()->getLocale());

        return $next($request);
    }
}
