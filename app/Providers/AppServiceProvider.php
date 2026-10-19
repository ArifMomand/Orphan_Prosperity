<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    View::composer('*', function ($view) {
        $locale = app()->getLocale();
        $meta   = config("locales.supported.$locale", config('locales.supported.en'));

        $view->with([
            'locale'   => $locale,
            'htmlLang' => $meta['html'],
            'dir'      => $meta['dir'],
            'isRtl'    => $meta['dir'] === 'rtl',
            'locales'  => config('locales.supported'),
        ]);
    });
}
}
