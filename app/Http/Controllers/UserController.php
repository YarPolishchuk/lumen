<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->register($request->all());

            return response()->json(['message' => 'User registered', 'user' => $user], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (!$this->userService->validationLogin($data)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return response()->json(['message' => 'Logged in successfully','response' => $this->userService->validationLogin($data)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function recoverPassword(Request $request): JsonResponse
    {
        $data = $request->all();
        $this->isAuthUser($data);

        if (!$this->userService->generateNewPassword($data)) {
            return response()->json(['message' => 'Password not updated'], 400);
        }
        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsersWithCompanies(Request $request): JsonResponse
    {
        $data = $request->header('auth');

        if(!$data) {
            throw new ModelNotFoundException();
        }

        if (!$this->userService->getUsersWithCompanies($data)) {
            return response()->json(['message' => 'Users not found'], 400);
        }
        return response()->json(['message' => $this->userService->getUsersWithCompanies($data)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addCompany(Request $request): JsonResponse
    {
        $data = $request->all();
        $this->isAuthUser($data);

        if (!$this->userService->isAddCompanies($data)) {
            return response()->json(['message' => 'Attach Error'], 400);
        }

        return response()->json(['message' => 'Company added successfully']);
    }
}
