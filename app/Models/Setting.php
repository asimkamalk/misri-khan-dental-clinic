<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Share settings with all views
View::composer('*', function ($view) {
    if (Schema::hasTable('settings') && class_exists('App\Models\Setting')) {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $view->with('settings', $settings);
    }
});
}
