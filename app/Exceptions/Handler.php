<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $exception, $request) {
            if ($this->isHttpException($exception)) {
                if (method_exists($exception, 'getStatusCode')) {
                    $status = $exception->getStatusCode();
                }
                return response()->json([
                    'status' => $status,
                    'request' => $request,
                    'exception' => $exception,
                    'message' => $exception->getMessage()
                ], $status);
            }

            if ($exception instanceof PosException) {
                $exception->build();
                return response()->json([
                    'code' => $exception->code,
                    'message' => $exception->message
                ], $exception->statusCode);
            }

            return parent::render($request, $exception);
        });
    }
}
