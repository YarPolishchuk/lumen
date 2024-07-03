<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'phone' => '123-456-7890',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'token' => Hash::make(Str::random())
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@example.com',
                'password' => Hash::make('password'),
                'phone' => '123-456-7891',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'token' => Hash::make(Str::random())

            ],
        ]);
    }
}
