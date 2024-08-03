namespace utomo\codage;

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
            __DIR__.'/Helpers' => app_path('Helpers'),
            __DIR__.'/Menu_access.php' => app_path('Menu_access.php'),
            __DIR__.'/../resources/views/components' => resource_path('views/components'),
            __DIR__.'/../resources/views/response' => resource_path('views/layouts'),
            __DIR__.'/../resources/views/proton' => resource_path('views/proton'),
            __DIR__.'/../resources/views/response' => resource_path('views/response'),
        ], 'package-assets');
    }
}
