<?php

namespace App\Filament\Resources\FinanceTransactionResource\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class StartTransactionDateFilter extends Filter
{
    public static function make(?string $name = 'start_transaction_date'): static
    {
        return parent::make($name)
            ->form([
                DatePicker::make('start_date')->default(self::startOfMonth())->label('Transação - Início')
            ])->query(function ($query, array $data) {
                if (!empty($data['start_date'])) {
                    return $query->where('transaction_date', '>=', $data['start_date']);
                }
                return $query;
            });
    }

    private static function startOfMonth(): string
    {
        return Carbon::now()->startOfMonth()->toDateString();
    }
}
