<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('company_user')->insert([
            [
                'user_id' => 1,
                'company_id' => 1,
            ],
            [
                'user_id' => 1,
                'company_id' => 2,
            ],
            [
                'user_id' => 2,
                'company_id' => 1,
            ],
        ]);
    }
}
