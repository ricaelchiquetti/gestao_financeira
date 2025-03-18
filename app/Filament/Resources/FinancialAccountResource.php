<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Filters\CompanyIdFilter;
use App\Filament\Resources\FinanceTransactionResource\Pages;
use App\Filament\Resources\FinancialAccountResource\Pages\CreateFinancialAccount;
use App\Filament\Resources\FinancialAccountResource\Pages\EditFinancialAccount;
use App\Filament\Resources\FinancialAccountResource\Pages\ListFinancialAccounts;
use App\Models\FinancialAccount;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class FinancialAccountResource extends Resource
{
    protected static ?string $model = FinancialAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Minha Empresa';

    protected static ?string $label = 'Conta Financeira';

    protected static ?string $pluralModelLabel = 'Contas Financeiras';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nome')->required()->maxLength(255),
                Hidden::make('company_id')->default(Auth::user()->company_id)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')->sortable()->searchable(),
            ])->filters([
                CompanyIdFilter::make()
            ])->actions([
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
            ])->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialAccounts::route('/'),
            'create' => CreateFinancialAccount::route('/create'),
            'edit' => EditFinancialAccount::route('/{record}/edit'),
        ];
    }
}
