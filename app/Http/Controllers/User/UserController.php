<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'resend', 'verify']);
        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-account')->only(['show', 'update']);
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->allowedAdminAction();

        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data                       = $request->all();
        $data['password']           = bcrypt($request->password);
        $data['verified']           = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['is_admin']           = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $rules = [
            'email'    => ['email', Rule::unique('users')->ignore($user->id)],
            'password' => ['min:6', 'confirmed'],
            'is_admin' => [Rule::in(User::ADMIN_USER, User::REGULAR_USER)],
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $request->email != $user->email) {
            $user->is_verified        = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email              = $request->email;
        }

        if ($request->has('is_admin')) {
            $this->allowedAdminAction();
            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', Response::HTTP_CONFLICT);
            }
            $user->is_admin = $request->is_admin;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->showOne($user);
    }

    /**
     * Return the current authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->showOne($user);
    }

    /**
     * Verify user by token.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($token): JsonResponse
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->is_verified        = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been successfully verified!');
    }

    /**
     * Resend email with verification token.
     */
    public function resend(User $user): JsonResponse
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified!', Response::HTTP_CONFLICT);
        }
        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('The verification email has just been resent.');
    }
}
