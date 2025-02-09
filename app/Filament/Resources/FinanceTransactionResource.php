<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceTransactionResource\Pages;
use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
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
                ->relationship('entity', 'name')->columnSpanFull(),

            DatePicker::make('transaction_date')->required()->label('Data de Transação'),
            DatePicker::make('due_date')->required()->label('Vencimento'),

            TextInput::make('value')->required()->numeric()->label('Valor'),

            Hidden::make('company_id')->default(Auth::user()->company_id)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('transaction_date')->date('d/m/Y')->sortable()->label('Data de Transação'),
            TextColumn::make('accountPlan.code')->searchable()->sortable()->label('Código'),
            TextColumn::make('entity.name')->searchable()->sortable()->label('Fornecedor/Cliente'),
            TextColumn::make('value')->sortable()->label('Valor'),
            TextColumn::make('due_date')->date('d/m/Y')->sortable()->label('Vencimento'),
            TextColumn::make('payment_method')->sortable()->label('Método de pagamento')->formatStateUsing(
                fn (string $state): string => match ($state) {
                    'cash' => 'Dinheiro',
                    'bank_transfer' => 'Transferência Bancária',
                })
            ])->filters([
                Filter::make('transaction_date')
                ->form([
                    DatePicker::make('start_date')->default(Carbon::now()->startOfMonth()->toDateString())->label('Início'),
                    DatePicker::make('end_date')->default(Carbon::now()->endOfMonth()->toDateString())->label('Final')
                ])->columns(2)->query(function ($query, array $data) {
                    if (!empty($data['start_date']) && !empty($data['end_date'])) {
                        return $query->whereBetween('transaction_date', [$data['start_date'], $data['end_date']]);
                    }
                    return $query;
                }),
               SelectFilter::make('type')->label('Tipo')->options([
                    'receivable' => 'Contas a Receber',
                    'payable' => 'Contas a Pagar',
                ]),
                SelectFilter::make('payment_method')->label('Método de pagamento')->options([
                    'cash' => 'Dinheiro',
                    'bank_transfer' => 'Transferência Bancária',
                ]),
            ], layout: FiltersLayout::AboveContent)->actions([
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
            ])->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Apagar selecionados'),
            ]);
    }

    public static function getTableQuery()
    {
        return parent::getTableQuery()->where('company_id', Auth::user()->company_id);
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
