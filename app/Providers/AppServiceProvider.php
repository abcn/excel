<?php

namespace App\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->registerBinds();
    }

    public function registerBinds()
    {
        $this->app->bind(
            \App\Repositories\Backend\Article\ArticleContract::class,
            \App\Repositories\Backend\Article\EloquentArticleRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Article\ArticleTypeContract::class,
            \App\Repositories\Backend\Article\EloquentArticleTypeRepository::class
        );
    }
}
