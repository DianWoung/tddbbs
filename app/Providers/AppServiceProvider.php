<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // Carbon::setLocale('zh');
//        \View::composer('*',function ($view){
//            $channels = \Cache::rememberForever('channels',function (){
//                return Channel::all();
//            });
//            $view->with('channels',$channels);
//        });

        Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_DEBUG')) {
            $this->app->register('VIACreative\SudoSu\ServiceProvider');
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');

        }
    }
}
