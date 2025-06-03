<?php

namespace App\Filament\Widgets;

use App\Models\FinanceTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class MonthlyIncomeWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $totals = FinanceTransaction::with('accountPlan.accountPlanType')
            ->where('due_date', '>=', $startDate)
            ->where('due_date', '<=', $endDate)
            ->get()
            ->groupBy(fn ($transaction) => $transaction->accountPlan->accountPlanType->type)
            ->map(fn ($group) => $group->sum('value'));

        return [
            Stat::make('Previsão de receita do Mês', 'R$ ' . number_format($totals->get('revenue', 0), 2, ',', '.')) 
                ->description('Total de previsão da receitas este mês')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Previsão de despesa do Mês', 'R$ ' . number_format($totals->get('expense', 0), 2, ',', '.')) 
                ->description('Total de previsão da despesas este mês')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
