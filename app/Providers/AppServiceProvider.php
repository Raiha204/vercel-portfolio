<?php

namespace App\Providers;

use App\Support\ContactChannels;
use Illuminate\Support\Facades\View;
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
        // The public pages all share the owner's details, the contact channels and the placeholder switch.
        View::composer(['layouts.app', 'home', 'projects.show', 'partials.contact', 'errors.404', 'errors::404'], function ($view) {
            $view->with('owner', config('portfolio.owner'))
                ->with('channels', ContactChannels::all())
                ->with('showPlaceholders', (bool) config('portfolio.show_placeholders'));
        });
    }
}
