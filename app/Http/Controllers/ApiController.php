<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Example for response examples value"
 * )
 *
 * @OA\PathItem(path="/api")
 */
class ApiController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function allowedAdminAction()
    {
        if (Gate::denies('admin-action')) {
            throw new AuthorizationException('This action is unauthorized');
        }
    }
}
