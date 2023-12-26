<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
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
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response and add CORS headers to pesponse.
     */
    public function render($request, Throwable $e): JsonResponse|Response|RedirectResponse
    {
        return $this->handleException($request, $e);
    }

    /**
     * Handle all types of exceptions.
     */
    public function handleException(Request $request, Throwable $exception): Response
    {
        switch (true):
            case $exception instanceof ValidationException:
                return $this->convertValidationExceptionToResponse($exception, $request);
            case $exception instanceof ModelNotFoundException:
                $modelName = strtolower(class_basename($exception->getModel()));

                return $this->errorResponse(
                    "Does not exist any {$modelName} with the specified identificator",
                    Response::HTTP_NOT_FOUND
                );
            case $exception instanceof AuthenticationException:
                return $this->unauthenticated($request, $exception);
            case $exception instanceof AuthorizationException:
                return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
            case $exception instanceof NotFoundHttpException:
                return $this->errorResponse('The specified resource can not be found', Response::HTTP_NOT_FOUND);
            case $exception instanceof MethodNotAllowedHttpException:
                return $this->errorResponse(
                    'The specified method for the request is invalid',
                    Response::HTTP_METHOD_NOT_ALLOWED
                );
            case $exception instanceof HttpException:
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            case $exception instanceof QueryException:
                $error_code = $exception->errorInfo[1];
                if ($error_code == 1451) {
                    return $this->errorResponse(
                        'Can not remove this resource permanently. It is related with any other resources',
                        Response::HTTP_CONFLICT
                    );
                }
            case $exception instanceof TokenMismatchException:
                return redirect()
                    ->back()
                    ->withInput($request->input());
            default:
                return $this->errorResponse('Unexpected exception. Try later.', Response::HTTP_INTERNAL_SERVER_ERROR);
        endswitch;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|RedirectResponse
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse('Unauthenticated', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Create a response object from the given validation exception.
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request): Response
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax()
                ? response()->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY)
                : redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors($errors);
        }

        return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Check either request is from API or from web.
     */
    private function isFrontend(Request $request): bool
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
