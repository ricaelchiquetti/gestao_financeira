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
        $query = $this->getPageTableQuery();

        $totalToPay = $query
            ->whereHas('accountPlan.accountPlanType', function ($q) {
                $q->where('type', 'expense');
            })->sum('value');

        $totalToReceive = $query
            ->whereHas('accountPlan.accountPlanType', function ($q) {
                $q->where('type', 'revenue'); 
            })->sum('value');

        $total = $totalToPay - $totalToReceive;

        return [
            Stat::make('Total a Receber', number_format($totalToReceive, 2, ',', '.'))
                ->description('receitas')
                ->color('success'),

            Stat::make('Total a Pagar', number_format($totalToPay, 2, ',', '.'))
                ->description('despesas')
                ->color('danger'),
            
            Stat::make('Total a Pagar', number_format($total, 2, ',', '.'))
                ->description('Total')
                ->color($total >= 0 ? 'success':'danger')
        ];
    }
}
