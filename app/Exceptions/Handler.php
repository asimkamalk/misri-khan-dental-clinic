<?php
// app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        TokenMismatchException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Custom exception handling
        $this->renderable(function (Throwable $exception, $request) {
            if ($request->expectsJson()) {
                return $this->handleApiException($exception, $request);
            }

            return $this->handleWebException($exception, $request);
        });
    }

    /**
     * Handle API exceptions.
     *
     * @param \Throwable $exception
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException(Throwable $exception, $request)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
            return response()->json(['message' => "No {$modelName} found."], 404);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $exception->validator->errors()->getMessages(),
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['message' => 'The requested resource was not found.'], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['message' => 'Method not allowed.'], 405);
        }

        if ($exception instanceof ThrottleRequestsException || $exception instanceof TooManyRequestsHttpException) {
            return response()->json(['message' => 'Too many requests. Please try again later.'], 429);
        }

        // Default error response for unexpected exceptions
        if (config('app.debug')) {
            return response()->json([
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ], 500);
        }

        return response()->json(['message' => 'Server error. Please try again later.'], 500);
    }

    /**
     * Handle Web exceptions.
     *
     * @param \Throwable $exception
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function handleWebException(Throwable $exception, $request)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()
                ->withInput($request->except('password'))
                ->with('error', 'Your session has expired. Please try again.');
        }

        if ($exception instanceof ThrottleRequestsException || $exception instanceof TooManyRequestsHttpException) {
            return response()->view('errors.429', [], 429);
        }

        return null;
    }
}