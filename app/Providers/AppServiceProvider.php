<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter; // Corrigido
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

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

        RateLimiter::for('user', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function ($request, array $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 60;
                    return response()->json([
                        'message' => 'Você excedeu o limite de requisições',
                        'retry_after_seconds' => (int) $retryAfter
                    ], 429);
                });
        });

        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(10),
                Limit::perMinute(3)->by($request->input('email'))
            ];
        });
    }
}
