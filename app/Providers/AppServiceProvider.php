<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fix for MySQL versions < 5.7.7 and MariaDB
        Schema::defaultStringLength(191);

        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Share settings with all views
        View::composer('*', function ($view) {
            if (Schema::hasTable('settings')) {
                $settings = Setting::all()->pluck('value', 'key')->toArray();
                $view->with('settings', $settings);
            }
        });

        // Custom Blade directives
        Blade::directive('setting', function ($key) {
            return "<?php echo getSetting({$key}); ?>";
        });

        Blade::directive('formatTime', function ($time) {
            return "<?php echo formatTime({$time}); ?>";
        });

        Blade::directive('money', function ($amount) {
            return "<?php echo number_format({$amount}, 2); ?>";
        });

        Blade::if('admin', function () {
            return auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin');
        });

        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->role === 'super_admin';
        });
    }
}