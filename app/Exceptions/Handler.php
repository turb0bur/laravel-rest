<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Asm89\Stack\CorsService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(\Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response and add CORS headers to pesponse.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }

    /**
     * Handle all types of exceptions.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function handleException($request, \Throwable $exception)
    {
        switch (true):
            case ($exception instanceof ValidationException):
                return $this->convertValidationExceptionToResponse($exception, $request);
            case($exception instanceof ModelNotFoundException):
                $modelName = strtolower(class_basename($exception->getModel()));

                return $this->errorResponse("Does not exist any {$modelName} with the specified identificator", 404);
            case($exception instanceof AuthenticationException):
                return $this->unauthenticated($request, $exception);
            case($exception instanceof AuthorizationException):
                return $this->errorResponse($exception->getMessage(), 403);
            case($exception instanceof NotFoundHttpException):
                return $this->errorResponse("The specified resource can not be found", 404);
            case($exception instanceof MethodNotAllowedHttpException):
                return $this->errorResponse('The specified method for the request is invalid', 405);
            case($exception instanceof HttpException):
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            case($exception instanceof QueryException):
                $error_code = $exception->errorInfo[1];
                if ($error_code == 1451) {
                    return $this->errorResponse('Can not remove this resource permanently. It is related with any other resources', 409);
                }
            case ($exception instanceof TokenMismatchException):
                return redirect()->back()->withInput(request()->input());
            default:
                return $this->errorResponse('Unexpected exception. Try later.', 500);
        endswitch;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse('Unauthenticated', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $e
     * @param \Illuminate\Http\Request                   $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        $errors = $exception->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) :
                redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    /**
     * Check either request is from API or from web.
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    private function isFrontend(Request $request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
