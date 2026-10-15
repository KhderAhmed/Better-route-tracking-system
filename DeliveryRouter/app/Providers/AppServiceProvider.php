<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
 public function boot(): void
{
    // السماح بـ 5 محاولات فقط كل دقيقة لكل هاتف/بريد إلكتروني + عنوان IP
    RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)->by($request->input('email').$request->ip());
    });
}
}
