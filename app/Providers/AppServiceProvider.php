<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // X-Powered-Byヘッダーを削除（セキュリティ対策）
        if (function_exists('header')) {
            header_remove('X-Powered-By');
        }
    }
}
