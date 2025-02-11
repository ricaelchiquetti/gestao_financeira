<?php

namespace App\Filament\Resources\CompanyResource\Filters;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;

class CompanyIdFilter
{
    public static function make()
    {
        return Filter::make('company_id')->form([
            self::getFieldCompanyId()
        ])->query(fn($query, $data) => $query->where('company_id', $data['company_id']));
    }

    private static function getFieldCompanyId()
    {
        if (Auth::user()->isMaster())
            return TextInput::make('company_id')->default(Auth::user()->company_id)->label('ID Empresa');

        return Hidden::make('company_id')->default(Auth::user()->company_id);
    }
}
