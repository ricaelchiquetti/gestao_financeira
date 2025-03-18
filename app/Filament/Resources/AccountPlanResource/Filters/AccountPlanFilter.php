<?php

namespace App\Filament\Resources\AccountPlanResource\Filters;

use Filament\Tables\Filters\SelectFilter;

class AccountPlanFilter
{
    public static function make()
    {
        return SelectFilter::make('account_plan_id')->label('Plano de Conta')
            ->relationship('accountPlan', 'code')
            ->searchable(['code', 'description'])
            ->multiple()
            ->getOptionLabelFromRecordUsing(function ($record) {
                return $record->code . ' - ' . $record->description;
            });
    }
}
