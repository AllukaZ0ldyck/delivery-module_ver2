<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
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
    public function boot(Request $request): void
    {
        Paginator::useBootstrapFive();

        if(Request::is('admin/*')) {

            Auth::shouldUse('admin');

            Gate::define('admin', function ($user) {
                return $user->user_type === 'admin';
            });

            Gate::define('delivery_man', function ($user) {
                return $user->user_type === 'delivery_man';
            });

            Gate::define('staff', function ($user) {
                return $user->user_type === 'staff';
            });

        }

        if(Request::is('customer/*')) {
            Auth::shouldUse('web');

            Gate::define('customer', function ($user) {
                return true;
            });



        }



    }
}
