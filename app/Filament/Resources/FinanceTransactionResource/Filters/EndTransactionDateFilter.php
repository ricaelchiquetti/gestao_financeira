<?php

namespace App\Filament\Resources\FinanceTransactionResource\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class EndTransactionDateFilter extends Filter
{
    public static function make(?string $name = 'end_transaction_date'): static
    {
        return parent::make($name)
            ->form([
                DatePicker::make('end_date')->default(self::endOfMonth())->label('Transação - Final')
            ])->query(function ($query, array $data) {
                if (!empty($data['end_date'])) {
                    return $query->where('transaction_date', '<=', $data['end_date']);
                }
                return $query;
            });
    }

    private static function endOfMonth(): string
    {
        return Carbon::now()->endOfMonth()->toDateString();
    }
}
