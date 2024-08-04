# codage
Codeton Generator

# Step

### 0. Install fresh laravel 10
`composer create-project laravel/laravel:^10.0 example-app`

### 1. Add Package laravel ui
`composer require laravel/ui`

### 2. Install  Bootstrap Auth Scaffolding
`php artisan ui bootstrap --auth`

### 3. Install & Compile Package
`npm install && npm run build`


### 6. Update session drive to database on .env
`SESSION_DRIVER=database`

### 8. Set queue connection to database on .env
`QUEUE_CONNECTION=database`


### 9. Migrate Database
`php artisan migrate`

### 10. Run Laravel
`php artisan serve`

<hr>
<br>

## Install Codage
### 1. Add Package utomo/codage
`composer require utomo/codage`

### 2. Publish
`php artisan vendor:publish --tag=utomo-codage`

### 3. Force to overwrite to new Version
`php artisan vendor:publish --tag=utomo-codage --force`

### 4. Add command to app>Console>Kernel

    protected $commands = [
        Commands\ProtonGenerate::class,
    ];

### 5. Use Bootstrap Pagination on app>Providers>AppServiceProvider
    use Illuminate\Pagination\Paginator;

    public function boot(): void
    {
        //
        Paginator::useBootstrap();
    }

### 6. Include default route to routes>web.php
    require __DIR__.'/default.php';

### 6. How to Generate Code
For generate codage skeleton, please refer to [Codefio](https://codefio.gitbook.io/codeton)
