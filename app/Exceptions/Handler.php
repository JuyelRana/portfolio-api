<?php

namespace App\Exceptions;

use App\Http\Helpers\APIHelpers;
use http\Exception\BadMethodCallException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof QueryException) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif($exception instanceof BadMethodCallException){
            $code = Response::HTTP_BAD_REQUEST;
            $message = "Method not found";//$exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $code = Response::HTTP_METHOD_NOT_ALLOWED;
            $message = $exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = "No record found";
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof TokenInvalidException) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof TokenBlacklistedException) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof TokenExpiredException) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof JWTException) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $exception->getMessage();
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        } elseif ($exception instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = "Requested url not found!";
            $response = APIHelpers::createAPIResponse(true, $code, $message);
            return new JsonResponse($response, Response::HTTP_OK);
        }

        return parent::render($request, $exception);
    }
}
