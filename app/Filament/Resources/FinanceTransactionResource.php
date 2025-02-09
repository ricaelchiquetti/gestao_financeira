<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceTransactionResource\Filters\AccountPlanTypeFilter;
use App\Filament\Resources\FinanceTransactionResource\Filters\EndTransactionDateFilter;
use App\Filament\Resources\FinanceTransactionResource\Filters\StartTransactionDateFilter;
use App\Filament\Resources\FinanceTransactionResource\Pages;
use App\Filament\Resources\FinanceTransactionResource\Widgets\FinanceTransactionStats;
use App\Models\FinanceTransaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class FinanceTransactionResource extends Resource
{
    protected static ?string $model = FinanceTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Transaçoes Financeiras';
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('account_plan_id')->required()->searchable()->label('Plano de Conta')
            ->relationship('accountPlan', 'code')->getOptionLabelFromRecordUsing(function ($record) {
                return $record->code . ' - ' . $record->description;
            })->columnSpanFull(),

            Select::make('entity_id')->required()->searchable()->label('Fornecedor/Cliente')
                ->relationship('entity', 'name'),

            TextInput::make('value')->required()->numeric()->label('Valor'),

            DatePicker::make('transaction_date')->required()->label('Data de Transação'),
            DatePicker::make('due_date')->required()->label('Vencimento'),

            Textarea::make('description')->label('Observação')->columnSpanFull(),

            Hidden::make('company_id')->default(Auth::user()->company_id)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('transaction_date')->toggleable()->date('d/m/Y')->sortable()->label('Data de Transação'),
            TextColumn::make('accountPlan.code')->toggleable()->searchable()->sortable()->label('Código'),
            TextColumn::make('description')->toggleable()->searchable()->sortable()->label('Descrição'),
            TextColumn::make('entity.name')->toggleable()->searchable()->sortable()->label('Fornecedor/Cliente'),
            TextColumn::make('value')->toggleable()->sortable()->label('Valor'),
            TextColumn::make('due_date')->toggleable()->date('d/m/Y')->sortable()->label('Vencimento'),
            ])->filters([
                StartTransactionDateFilter::make(),
                EndTransactionDateFilter::make(),
                AccountPlanTypeFilter::make()
            ], layout: FiltersLayout::AboveContent)->actions([
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
            ])->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getTableQuery()
    {
        return parent::getTableQuery()->where('company_id', Auth::user()->company_id);
    }

    public static function getWidgets(): array
    {
        return [
            FinanceTransactionStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinanceTransactions::route('/'),
            'create' => Pages\CreateFinanceTransaction::route('/create'),
            'edit' => Pages\EditFinanceTransaction::route('/{record}/edit'),
        ];
    }
}
