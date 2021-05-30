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

### Step 6: To use tokens for users [in user model]
~~~~
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
~~~~

### Step 7: Let's create the seeder for the User Model
~~~~
First make the facker UserFactory.php

public function definition()
{
  $gender = $this->faker->randomElement(['male', 'female']);
  return [
  'first_name' => $this->faker->firstName($gender),
  'last_name' => $this->faker->firstName($gender),
  'username' => $this->faker->userName,
  'phone' => $this->faker->phoneNumber,
  'email' => $this->faker->unique()->safeEmail(),
  'email_verified_at' => now(),
  'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
  'remember_token' => Str::random(10),
  ];
}

Then seed the UserSeeder class

php artisan db:seed --class=UserSeeder
~~~~
