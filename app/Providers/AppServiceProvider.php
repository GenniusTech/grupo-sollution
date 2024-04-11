<?php

namespace App\Providers;

use App\Models\Message;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {

    }

    public function boot(): void {

        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            
            $messages = Message::all();
            $view->with(['messages' => $messages]);
        });
    }
}
