<?php

namespace App\Filament\Resources\FinanceTransactionResource\Filters;

use Filament\Tables\Filters\SelectFilter;

class AccountPlanTypeFilter
{
    public static function make()
    {
        return SelectFilter::make('type')
            ->label('Tipo')
            ->options([
                'revenue' => 'Contas a Receber',
                'expense' => 'Contas a Pagar',
            ])
            ->query(function ($query, $value = []) {
                if ($value) {
                    return $query->whereHas('accountPlan.accountPlanType', function ($query) use ($value) {
                        $query->where('type', $value);
                    });
                }
                return $query;
            });
    }
}
