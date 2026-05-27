<?php

namespace Modules\LiveStream\Providers;

use App\Contracts\Modules\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\LiveStream\Models\LiveStreamPilot;

/**
 * @package Modules\LiveStream
 */
class AppServiceProvider extends ServiceProvider
{
    private $moduleSvc;

    protected $defer = false;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->moduleSvc = app('App\Services\ModuleService');

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerLinks();

        // Load module-owned migrations (creates livestream_pilots, passenger_interactions)
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        // Inject stream data into ASA_THEME views without touching the core User model
        $this->registerViewComposers();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }

    /**
     * View Composers — inject LiveStream data into theme views.
     * This avoids modifying the core User model or the users table schema.
     */
    protected function registerViewComposers(): void
    {
        // --- Pilot Profile page ---
        // Injects $livestreamProfile (LiveStreamPilot|null) for the viewed pilot
        View::composer(
            'layouts.ASA_THEME.profile.index',
            function ($view) {
                $data = $view->getData();
                $userId = optional($data['user'] ?? null)->id;

                $livestreamProfile = $userId
                    ? LiveStreamPilot::where('user_id', $userId)->first()
                    : null;

                $view->with('livestreamProfile', $livestreamProfile);
            }
        );

        // --- Pilots Roster page ---
        // Injects $liveUserIds (int[]) — IDs of pilots currently live
        View::composer(
            'layouts.ASA_THEME.users.table',
            function ($view) {
                $liveUserIds = LiveStreamPilot::liveUserIds();
                $view->with('liveUserIds', $liveUserIds);
            }
        );
    }

    /**
     * Add module links here.
     */
    public function registerLinks(): void
    {
        // Admin link
        $this->moduleSvc->addAdminLink('LiveStream', '/admin/livestream');
    }

    /**
     * Register config.
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('livestream.php'),
        ], 'livestream');

        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'livestream');
    }

    /**
     * Register views.
     */
    public function registerViews()
    {
        $viewPath   = resource_path('views/modules/livestream');
        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([$sourcePath => $viewPath], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return str_replace('default', setting('general.theme'), $path) . '/modules/livestream';
        }, \Config::get('view.paths')), [$sourcePath]), 'livestream');
    }

    /**
     * Register translations.
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/livestream');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'livestream');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'livestream');
        }
    }
}
