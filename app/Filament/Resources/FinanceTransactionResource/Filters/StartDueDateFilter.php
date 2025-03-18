<?php

namespace App\Filament\Resources\FinanceTransactionResource\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class StartDueDateFilter extends Filter
{
    public static function make(?string $name = 'start_due_date'): static
    {
        return parent::make($name)
            ->form([
                DatePicker::make('start_date')->default(self::startOfMonth())->label('Vencimento - Início')
            ])->query(function ($query, array $data) {
                if (!empty($data['start_date'])) {
                    return $query->where('due_date', '>=', $data['start_date']);
                }
                return $query;
            });
    }

    private static function startOfMonth(): string
    {
        return Carbon::now()->startOfMonth()->toDateString();
    }
}
