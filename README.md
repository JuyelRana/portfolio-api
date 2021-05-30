# Portfolio-api
Portfolio api using laravel 

## Getting Started

### Step 1: Set up database in .env

~~~~
DB_DATABASE=[Your DB Name]
DB_USERNAME=[Your DB Username]
DB_PASSWORD=[Your DB Password]
~~~~

## Api Authentication [Laravel JWT]

### What is JSON Web Token?
JSON Web Token (JWT) is an open standard (RFC 7519), and it represents a compact and self-contained method for securely transmitting information between parties as a JSON object. Digital signature makes the data transmission via JWT trusted and verified. JWTs built upon the secret HMAC algorithm or a public/private key pair using RSA or ECDSA.

### Why is JWT Required?

JWT is used for Authorization and information exchange between server and client. It authenticates the incoming request and provides an additional security layer to REST API, which is best for security purposes.

### How does JWT Work?
User information such as username and password is sent to the web-server using HTTP GET and POST requests. The web server identifies the user information and generates a JWT token and sends it back to the client. Client store that token into the session and also set it to the header. On the next HTTP call, that token is verified by the server, which returns the response to the client.

### JSON Web Token Structure

JSON Web Tokens contains three parts separated by dots (.) In its dense form.

~~~~
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9
.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3RoZXNob3BseV9uZXRfYXBpXC9hcGlcL3YxXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyMjA1MzcyMiwiZXhwIjoxOTMzMDkzNzIyLCJuYmYiOjE2MjIwNTM3MjIsImp0aSI6IlF3a25RaHRuZUU4VG85UnAiLCJzdWIiOjMzOSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9
.qnLLmbwpDS7dh31l8U7VGBzQ3oud79fihJ_5uSsUcVY
~~~~

- Header
- Payload
- Signature


## Install & Configure JWT Authentication Package

Execute the following command to install [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth), It is a third-party JWT package and allows user authentication using JSON Web Token in Laravel & Lumen securely.

`composer require tymon/jwt-auth`

Above command installed the jwt-auth package in the vendor folder, now we have to go to **config/app.php** file and include the laravel service provider inside the `providers` array.

Also include the **JWTAuth** and **JWTFactory** facades inside the `aliases` array.

````
'providers' => [
    ....
    ....
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
],
'aliases' => [
    ....
    'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
    'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
    ....
],
````

In the next step, we have to publish the package’s configuration, following command copy JWT Auth files from vendor folder to **config/jwt.php** file.

```` 
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
````

For handling the token encryption, generate a secret key by executing the following command.
```` 
php artisan jwt:secret
````
We have successfully generated the JWT Secret key, and you can check this key inside the **.env** file.
```` 
JWT_SECRET=secret_jwt_string_key
````

### Set Up User Model
Laravel comes with a pre-defined **User** model; we can use the User model for authentication process. In this step, we will learn how to implement the jwt-auth package in a user model.

Define **Tymon\JWTAuth\Contracts\JWTSubject** contract before the User model. This method wants you to define the two methods:

- **getJWTIdentifier():** Get the identifier that will be stored in the subject claim of the JWT.
- **getJWTCustomClaims():** Return a key value array, containing any custom claims to be added to the JWT.

### Configure Auth guard
Now, we need to set up the JWT Auth Guard to secure the Laravel application’s authentication process. Laravel guard uses the session driver to protect the guards. However, we set the defaults guard to api, and the api guards is ordered to use jwt driver

Place the following code in **config/auth.php** file.

```` 
<?php

return [

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],


    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
    ],
````

### Make a Helper Class for formation the api response

```` 
<?php

namespace App\Http\Helpers;

class APIHelpers
{
    public static function createAPIResponse($is_error, $code, $message, $content = null)
    {
        $result = [];

        if ($is_error) {
            $result['success'] = false;
            $result['code'] = $code;
            $result['message'] = $message;

            if ($content != null) {
                $result['errors'] = $content;
            }
        } else {
            $result['success'] = true;
            $result['code'] = $code;
            $result['message'] = $message;

            if ($content != null) {
                $result['data'] = $content;
            }
        }
        return $result;
    }
}
````

### Make a middleware for authenticate jwt token
`php artisan make:middleware JWT`

In the **app\Http\Middleware\JWT** file put the following code

```` 
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        JWTAuth::parseToken()->authenticate();

        return $next($request);
    }
}
````
