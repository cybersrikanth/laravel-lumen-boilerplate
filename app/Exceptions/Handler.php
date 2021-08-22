<?php

namespace App\Exceptions;

use App\Exceptions\JWT\JwtException;
use App\Exceptions\Session\SessionException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    private function jsonErrorResponse(int $status_code = 500, string $message = 'Internal Server Error')
    {
        return response()->json([
            'message' => $message
        ], $status_code);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $rendered = parent::render($request, $exception);

        if ($rendered instanceof JsonResponse)
            return $rendered;

        // Handle Custom exceptions
        if ($exception instanceof JwtException)
            return $this->jsonErrorResponse(401, $exception->getMessage());

        if ($exception instanceof SessionException)
            return $this->jsonErrorResponse(401, $exception->getMessage());

        if ($exception instanceof ValidationException)
            return new JsonResponse($exception->errors(), 422);

        // Handle non json error
        return $this->jsonErrorResponse($rendered->getStatusCode(), $exception->getMessage());
    }
}
