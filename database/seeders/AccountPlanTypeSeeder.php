<?php

namespace Database\Seeders;

use App\Models\AccountPlanType;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountPlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        AccountPlanType::create([
            'name' => 'Ativo',
            'type' => 'revenue',
            'company_id' => $company->id
        ]);
        AccountPlanType::create([
            'name' => 'Receita',
            'type' => 'revenue',
            'company_id' => $company->id
        ]);
        
        AccountPlanType::create([
            'name' => 'Passivo',
            'type' => 'expense',
            'company_id' => $company->id
        ]);
        AccountPlanType::create([
            'name' => 'Despesa',
            'type' => 'expense',
            'company_id' => $company->id
        ]);
    }
}
