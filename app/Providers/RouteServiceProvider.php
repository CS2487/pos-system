<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // يجب تعريف namespace إذا كنت تستخدمه في Laravel 8 أو أقدم
    // في Laravel 9/10/11 عادة يتم تجاهل الـ namespace واستخدام الاسم الكامل للكنترولر
    protected $namespace = 'App\Http\Controllers';

    public function boot(): void
    {
        //$this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->namespace($this->namespace) // هذا يعمل هنا لأن $this يشير لـ RouteServiceProvider
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    // ... باقي دوال الفئة ...
}