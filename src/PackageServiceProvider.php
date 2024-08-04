<?php
namespace Utomo\Codage;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register any package services.
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../public/theme' => public_path('theme'),
            __DIR__.'/Codeton' => app_path('Codeton'),
            __DIR__.'/Console/Commands' => app_path('Console/Commands'),
            
            //publish controller scaffolding
            __DIR__.'/Controllers/Codeton.php' => app_path('Http/Controllers/Codeton.php'),
            __DIR__.'/Controllers/Auth/LoginController.php' => app_path('Http/Controllers/Auth/LoginController.php'),
            __DIR__.'/Controllers/root' => app_path('Http/Controllers/root'),
            __DIR__.'/Controllers/setting' => app_path('Http/Controllers/setting'),

            __DIR__.'/Helpers' => app_path('Helpers'),
            __DIR__.'/Codeton/GenerateMenuSidebar.php' => app_path('GenerateMenuSidebar.php'),
            
            //publish route scaffolding
            __DIR__.'/../routes/root' => base_path('routes/root'),
            __DIR__.'/../routes/setting' => base_path('routes/setting'),
            __DIR__.'/../routes/default.php' => base_path('routes/default.php'),

            //publish db scaffold
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
            __DIR__.'/../database/seeders' => base_path('database/seeders'),

            //publish view scaffolding
            __DIR__.'/../resources/views/components' => resource_path('views/components'),
            __DIR__.'/../resources/views/response' => resource_path('views/layouts'),
            __DIR__.'/../resources/views/proton' => resource_path('views/proton'),
            __DIR__.'/../resources/views/response' => resource_path('views/response'),
            __DIR__.'/../resources/views/root' => resource_path('views/root'),
            __DIR__.'/../resources/views/setting' => resource_path('views/setting'),
            __DIR__.'/../resources/views/layouts' => resource_path('views/layouts'),
            __DIR__.'/../resources/views/home.blade.php' => resource_path('views/home.blade.php'),
        ], 'utomo-codage');
    }
}
