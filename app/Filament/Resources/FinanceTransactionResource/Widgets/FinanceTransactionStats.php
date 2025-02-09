<?php

namespace App\Filament\Resources\FinanceTransactionResource\Widgets;

use App\Filament\Resources\FinanceTransactionResource\Pages\ListFinanceTransactions;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceTransactionStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListFinanceTransactions::class;
    }

    protected function getStats(): array
    {
        $totals = $this->getPageTableQuery()
            ->whereHas('accountPlan.accountPlanType') 
            ->with('accountPlan.accountPlanType') 
            ->get()
            ->groupBy(fn ($transaction) => $transaction->accountPlan->accountPlanType->type)
            ->map(fn ($group) => $group->sum('value'));

        $total = $totals->get('revenue', 0) - $totals->get('expense', 0);

        return [
            Stat::make('Total a Receber', number_format($totals->get('revenue', 0), 2, ',', '.'))
                ->description('receitas')
                ->color('success'),

            Stat::make('Total a Pagar', number_format($totals->get('expense', 0), 2, ',', '.'))
                ->description('despesas')
                ->color('danger'),
            
            Stat::make('Total', number_format($total, 2, ',', '.'))
                ->description('Total')
                ->color($total >= 0 ? 'success' : 'danger')
        ];
    }
}
