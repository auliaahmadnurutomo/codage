# codage
Codeton Generator

## composer require utomo/codage:v1.1.5

## php artisan vendor:publish --tag=utomo-codage --force


# Step
### 1. Add Package laravel ui
`composer require laravel/ui`

### 2. Install  Bootstrap Auth Scaffolding
`php artisan ui bootstrap --auth`

### 3. Install & Compile Package
`npm install && npm run build`

### 5. Set session to table database
`php artisan session:table`

### 6. Update session drive to database on .env
`SESSION_DRIVER=database`

### 7. Add queue
`php artisan queue:table`

### 8. Set queue connection to database on .env
`QUEUE_CONNECTION=database`


### 9. Migrate Database
`php artisan migrate`

### 10. Run Laravel
`php artisan serve`

<hr>
<br>

## Install Migration Generator

`composer require --dev kitloong/laravel-migrations-generator`

### 1. Generate all tables

`php artisan migrate:generate`

### 2. Generate Specified Tables

`php artisan migrate:generate --tables="table1,table2"`

### 3. Squash Migrations into single file

`php artisan migrate:generate --squash`
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

### 6. How to Generate Code
For generate codage skeleton, please refer to [Codefio](https://codefio.gitbook.io/codeton)


## Altering Table add column(s)

    public function up()
    {
        DB::statement("ALTER TABLE table ADD column custom_type NOT NULL");
    }

## Optional install seeder generator

### 1. Install Package Orangehill
`composer require orangehill/iseed`

### 2. Generate data from tables
`php artisan iseed my_table,another_table`