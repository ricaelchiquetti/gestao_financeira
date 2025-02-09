<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'admin' => true,
            'master' => true,
            'password' => bcrypt('123456'),
            'company_id' => $company->id
        ]);
    }
}
