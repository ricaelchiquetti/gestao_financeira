<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\FinanceTransactionResource\Filters\CompanyIdFilter;
use App\Models\Company;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 999;

    protected static ?string $label = 'Empresas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nome')->required(),
            TextInput::make('cnpj')->label('CNPJ')->mask('99.999.999/9999-99')->nullable(),
            Toggle::make('active')->label('Ativo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nome')->searchable()->sortable(),
            TextColumn::make('cnpj')->label('CNPJ')->searchable()->sortable(),
            IconColumn::make('active')->label('Ativo')->boolean()->sortable(),
        ])->filters([
            CompanyIdFilter::make()
        ])->actions([
            EditAction::make()->label(''),
            DeleteAction::make()->label(''),
        ])->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('company_id', auth()->user()->company_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
