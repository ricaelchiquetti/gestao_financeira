<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountPlanResource\Pages;
use App\Filament\Resources\FinanceTransactionResource\Filters\CompanyIdFilter;
use App\Models\AccountPlan;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AccountPlanResource extends Resource
{
    protected static ?string $model = AccountPlan::class;

    protected static ?string $label = 'Plano de Contas';

    protected static ?string $navigationGroup = 'Plano de Contas';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('code')->required()->label('Código'),
            Select::make('account_plan_type_id')->required()->label('Tipo')->relationship('accountPlanType', 'name'),
            TextInput::make('description')->required()->label('Descrição')->columnSpan(2),
            Hidden::make('company_id')->default(Auth::user()->company_id)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->sortable()->searchable()->label('Código'),
            TextColumn::make('description')->sortable()->searchable()->label('Descrição'),
            TextColumn::make('accountPlanType.name')->sortable()->searchable()->label('Plano de Contas'),
        ])->filters([
            CompanyIdFilter::make()
        ])->actions([
            EditAction::make()->label(''),
            DeleteAction::make()->label(''),
        ])->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountPlans::route('/'),
            'create' => Pages\CreateAccountPlan::route('/create'),
            'edit' => Pages\EditAccountPlan::route('/{record}/edit'),
        ];
    }
}
