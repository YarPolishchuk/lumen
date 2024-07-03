<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isAuthUser(array $data): bool
    {
        $user = $this->userService->findUserByEmail($data);

        if (empty($user->token)) {
            throw new ModelNotFoundException();
        }
        return true;
    }
}
