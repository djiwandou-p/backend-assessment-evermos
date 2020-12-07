<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Http\Controllers\ApiController;

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
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }

    /**
     * @param $request
     * @param \Exception $exception
     * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
            return (new ApiController)->sendResponse([], "Data not found.", 404, false);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return (new ApiController)->sendResponse([], 'The specified method for the request is invalid', 405, false);
        }

        if ($exception instanceof NotFoundHttpException) {
            return (new ApiController)->sendResponse([], 'The specified URL cannot be found', 404, false);
        }

        if ($exception instanceof HttpException) {
            return response($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode === 1451) {
                return (new ApiController)->sendResponse([], 'Cannot remove this resource permanently. It is related with another resource', 409, false);
            }
        }
        return (new ApiController)->sendResponse([], 'Internal Server Error', 500, false);
    }
}
