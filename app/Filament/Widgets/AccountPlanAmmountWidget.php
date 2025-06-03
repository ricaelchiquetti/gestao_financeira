<?php

namespace App\Filament\Widgets;

use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AccountPlanAmmountWidget extends ChartWidget
{
    protected static ?string $heading = 'Receitas por Plano (Mês Atual)';

    protected int | string | array $columnSpan = 4;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $revenueByCategory = FinanceTransaction::join('account_plans', 'finance_transactions.account_plan_id', '=', 'account_plans.id')
            ->join('account_plan_types', 'account_plans.account_plan_type_id', '=', 'account_plan_types.id')
            ->where('account_plan_types.type', 'revenue')
            ->whereBetween('finance_transactions.created_at', [$startDate, $endDate])
            ->select('account_plans.description as category_name', DB::raw('SUM(finance_transactions.value) as total_value'))
            ->groupBy('account_plans.description')
            ->orderBy('total_value', 'desc')
            ->pluck('total_value', 'category_name');

        $labels = $revenueByCategory->keys()->map(fn($name) => (string) $name)->toArray();
        $data = $revenueByCategory->values()->map(fn($value) => $value / 100)->toArray(); // Convertendo de centavos

        return [
            'datasets' => [
                [
                    'label' => 'Receitas por Categoria',
                    'data' => $data,
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                        '#FFCD56', '#C9CBCF', '#3FC3A0', '#F9762D', '#8B5CF6', '#FACC15'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Ou 'doughnut' para um gráfico de rosca
    }
}
