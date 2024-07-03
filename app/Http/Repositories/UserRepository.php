<?php

namespace App\Http\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    /**
     * @param array $data
     * @return Model
     */
    public function createUser(array $data): Model
    {
        return User::query()->create($data);
    }

    /**
     * @param Model $user
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(Model $user, string $newPassword): bool
    {
         return $user->update(['password' => $newPassword]);
    }

    /**
     * @param Model $user
     * @return array
     */
    public function findUserWithCompanies(Model $user): array
    {
        return User::with(['companies:id,position,phone,description'])
            ->where('id', $user->id)
            ->get(['id', 'first_name', 'last_name', 'email', 'phone'])
            ->toArray();
    }


    public function findUserByPhone(array $data): ?Model
    {
        return User::query()->where('phone', $data['phone'])->first();
    }

    public function findUserByToken(string $data): ?Model
    {
        return User::query()->where('token', $data)->first();
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function findUserByEmail(array $data): ?Model
    {
        return User::query()->where('email', $data['email'])->first();
    }

    /**
     * @param Model|null $user
     * @param array $data
     * @return void
     */
    public function createCompany(?Model $user, array $data): void
    {
        $company = Company::query()->create($data);

        $user->companies()->attach($company->id);
    }
}
