<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\APIHelpers;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\Auth\AuthResource;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login', 'register']]);
    }

    /**
     * @return JsonResponse
     */
    public function allUsers()
    {
        $users = null;

        try {
            $users = User::all();

            $code = Response::HTTP_FOUND;

            $message = $users->count() . " Users Found!";

            $response = APIHelpers::createAPIResponse(false, $code, $message, AuthResource::collection($users));

        } catch (QueryException $exception) {

            $code = $exception->getCode();

            $message = $exception->getMessage();

            $response = APIHelpers::createAPIResponse(true, $code, $message);
        }

        return new JsonResponse($response, Response::HTTP_OK);

    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::guard('api')->attempt($credentials)) {

            $code = Response::HTTP_UNAUTHORIZED;

            $message = "Credentials does not matches.";

            $response = APIHelpers::createAPIResponse(true, $code, $message);

            return new JsonResponse($response, Response::HTTP_OK);
        }

        return $this->createNewToken($token);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'active' => $request->active
            ]);

            $code = Response::HTTP_CREATED;
            $message = "User Successfully Registered.";
            $response = APIHelpers::createAPIResponse(false, $code, $message, new AuthResource($user));

        } catch (QueryException $exception) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
            $response = APIHelpers::createAPIResponse(true, $code, $message);

        }

        return new JsonResponse($response, Response::HTTP_OK);
    }


    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
            $code = Response::HTTP_OK;
            $message = "User successfully signed out.";
            $response = APIHelpers::createAPIResponse(false, $code, $message);
        } else {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = "You are not logged in.";
            $response = APIHelpers::createAPIResponse(true, $code, $message);
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }


    /**
     * @return JsonResponse
     */

    public function userProfile()
    {
        if (auth()->user()) {
            $code = Response::HTTP_OK;
            $message = "User Profile Found.";
            $response = APIHelpers::createAPIResponse(false, $code, $message, new AuthResource(auth()->user()));
        } else {
            $code = Response::HTTP_BAD_REQUEST;
            $message = "User Profile Not Found.";
            $response = APIHelpers::createAPIResponse(true, $code, $message);

        }
        return new JsonResponse($response, Response::HTTP_OK);
    }


    protected function createNewToken($token)
    {

        if ($token != null) {

            $code = Response::HTTP_OK;

            $message = "Login Success";

            $access_token = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 30,
                'user' => new AuthResource(auth()->user())
            ];

            $response = APIHelpers::createAPIResponse(false, $code, $message, $access_token);

            return new JsonResponse($response, Response::HTTP_OK);
        }

    }

    public function payload()
    {
        if (auth()->check()) {
            $code = Response::HTTP_FOUND;
            $message = "Payload Data";
            $response = APIHelpers::createAPIResponse(false, $code, $message, auth()->payload());
        } else {
            $code = Response::HTTP_NO_CONTENT;
            $message = "Payload Not Generated";
            $response = APIHelpers::createAPIResponse(true, $code, $message);
        }

        return new JsonResponse($response, Response::HTTP_OK);

    }
}
