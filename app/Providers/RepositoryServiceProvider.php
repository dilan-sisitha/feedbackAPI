<?php

namespace App\Providers;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\FeedbackRepositoryInterface;
use App\Interfaces\SettingsRepositoryInterface;
use App\Interfaces\SiteDataRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\SiteDataRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class,BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class,FeedbackRepository::class);
        $this->app->bind(SiteDataRepositoryInterface::class,SiteDataRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class,SettingsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
