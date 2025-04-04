<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountPlanResource\Filters\AccountPlanFilter;
use App\Filament\Resources\FinanceTransactionResource\Filters\AccountPlanTypeFilter;
use App\Filament\Resources\CompanyResource\Filters\CompanyIdFilter;
use App\Filament\Resources\FinanceTransactionResource\Filters\EndDueDateFilter;
use App\Filament\Resources\FinanceTransactionResource\Filters\StartDueDateFilter;
use App\Filament\Resources\FinanceTransactionResource\Pages;
use App\Filament\Resources\FinanceTransactionResource\Widgets\FinanceTransactionStats;
use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-m-document-currency-dollar';

    protected static ?string $label = 'Transações Financeiras';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('account_plan_id')->required()->label('Plano de Conta')
                ->relationship('accountPlan', 'code')
                ->searchable(['code', 'description'])
                ->getOptionLabelFromRecordUsing(function ($record) {
                    return $record->code . ' - ' . $record->description;
                })->columnSpanFull(),

            TextInput::make('description')->label('Descrição'),

            TextInput::make('document_number')->label('Nº Documento'),

            Select::make('entity_id')->searchable()->label('Fornecedor/Cliente')->relationship('entity', 'name'),

            ...($form->getOperation() === 'edit' ? self::updateForm() : self::insertForm()),

            Hidden::make('company_id')->default(Auth::user()->company_id)
        ]);
    }

    static protected function insertForm(): array
    {
        return [
            TextInput::make('installments')->numeric()->label('Quantidade de Parcelas')->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, Get $get) => self::calculate($set, $get)),

            TextInput::make('installments_value')->numeric()->label('Valor Total')->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, Get $get) => self::calculate($set, $get))
                ->visible(fn(Get $get): bool => $get('installments') > 1),

            DatePicker::make('installments_due')->label('Data da Primeira Parcela')->live(onBlur: true)
                ->afterStateUpdated(fn(Set $set, Get $get) => self::calculate($set, $get))
                ->visible(fn(Get $get): bool => $get('installments') > 1),

            Repeater::make('installments_fields')->label('')->columnSpanFull()
                ->schema(self::updateForm())->columns(4)->addable(false)->deletable(false)->reorderable(false),
        ];
    }

    static protected function updateForm()
    {
        return [
            TextInput::make('value')->required()->numeric()->label('Valor'),
            DatePicker::make('due_date')->required()->label('Vencimento'),
            DatePicker::make('transaction_date')->label('Data de Transação'),
            Select::make('financial_account_id')->label('Conta Financeira')->relationship('financialAccount', 'name'),
        ];
    }

    static function calculate(Set $set, Get $get)
    {
        $installments = max(1, (int) $get('installments'));
        $totalValue = (float) $get('installments_value');
        $dueDate = $get('installments_due') ? Carbon::createFromFormat('Y-m-d', $get('installments_due')) : null;

        $installmentsFields = [];
        for ($i = 1; $i <= $installments; $i++) {
            $installmentsDue = null;
            if ($get('installments_due')) {
                $installmentsDue = $dueDate->copy()->addMonths($i - 1);
                if ($installmentsDue->isWeekend()) {
                    if ($installmentsDue->isSaturday()) {
                        $installmentsDue->subDay();
                    }

                    if ($installmentsDue->isSunday()) {
                        $installmentsDue->subDays(2);
                    }
                }
            }

            $installmentsFields[] = [
                'due_date' => $installmentsDue?->format('Y-m-d'),
                'value' => number_format($totalValue / $installments, 2, '.', ''),
            ];
        }
        $set('installments_fields', $installmentsFields);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('accountPlan.code')->toggleable()->searchable()->sortable()->label('Código'),
            TextColumn::make('description')->toggleable()->searchable()->sortable()->label('Descrição'),
            TextColumn::make('entity.name')->toggleable()->searchable()->sortable()->label('Fornecedor/Cliente'),
            TextColumn::make('value')->toggleable()->sortable()->label('Valor'),
            TextColumn::make('due_date')->toggleable()->date('d/m/Y')->sortable()->label('Vencimento'),
        ])->filters([
            StartDueDateFilter::make(),
            EndDueDateFilter::make(),
            AccountPlanFilter::make(),
            AccountPlanTypeFilter::make(),
            CompanyIdFilter::make()
        ], layout: FiltersLayout::AboveContent)->actions([
            EditAction::make()->label(''),
            DeleteAction::make()->label(''),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
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
