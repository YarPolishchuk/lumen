<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;



class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return Model
     * @throws ValidationException
     */
    public function register(array $data): Model
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data['password'] = Hash::make($data['password']);
        $data['token'] = Hash::make(Str::random());

        return $this->userRepository->createUser($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function validationLogin(array $data): array
    {
        $user = $this->findUserByEmail($data);

        if (!Hash::check($data['password'], $user->password)) {
            return [];
        }
        return [
            'token' => $user->token
        ];
    }

    public function generateNewPassword(array $data): bool
    {
        $user = $this->findUserByEmail($data);

        $newPassword = Hash::make(Str::random(10));

        return $this->userRepository->updatePassword($user, $newPassword);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function findUserByEmail(array $data): Model
    {
        $user = $this->userRepository->findUserByEmail($data);
        if (!$user) {
            throw new ModelNotFoundException();
        }
        return $user;
    }

    /**
     * @param string $data
     * @return array
     */
    public function getUsersWithCompanies(string $data): array
    {
        $user =  $this->findUserByToken($data);
        $users = $this->userRepository->findUserWithCompanies($user);
        if (!$users) {
            throw new ModelNotFoundException();
        }
        return $users;
    }

    /**
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function isAddCompanies(array $data): bool
    {
        $validator = Validator::make($data, [
            'position' => 'required|string',
            'phone' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = $this->userRepository->findUserByPhone($data);
        $this->userRepository->createCompany($user, $data);
        return true;
    }

    public function findUserByToken(string $data): Model
    {
        $user = $this->userRepository->findUserByToken($data);
        if (!$user) {
            throw new ModelNotFoundException();
        }
        return $user;
    }
}
