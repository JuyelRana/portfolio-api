# Portfolio-api
Portfolio api using laravel 

## Getting Started

### Step 1: Set up database in .env

~~~~
DB_DATABASE=[Your DB Name]
DB_USERNAME=[Your DB Username]
DB_PASSWORD=[Your DB Password]
~~~~

## Api Authentication [Laravel Sanctum]

### Step 2: Install Laravel Sanctum
~~~~
composer require laravel/sanctum
~~~~

### Step 3: Publish the Sanctum configuration and migration files.
~~~~
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
~~~~

### Step 4: Run your database migrations.
~~~~
php artisan migrate 
~~~~

### Step 5: Add the Sanctum's middleware to [/app/Http/Kernel.php] file
~~~~
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful; [Import this in top]

Add the following line inside [protected $middlewareGroups] 

'api' => [
     EnsureFrontendRequestsAreStateful::class,
     'throttle:api',
     \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
~~~~
