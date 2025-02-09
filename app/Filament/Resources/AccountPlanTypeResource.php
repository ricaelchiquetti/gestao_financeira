<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountPlanTypeResource\Pages;
use App\Models\AccountPlanType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AccountPlanTypeResource extends Resource
{
    protected static ?string $model = AccountPlanType::class;

    protected static ?string $navigationIcon = 'heroicon-m-tag';

    protected static ?string $navigationGroup = 'Plano de Contas';

    protected static ?string $label = 'Tipos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nome')->required()->maxLength(255),
            Select::make('type')->required()->label('Tipo')
            ->options([
                'revenue' => 'Ativo/Receita', 'expense' => 'Passivo/Despesa'
            ]),
            Hidden::make('company_id')->default(Auth::user()->company_id)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nome')->sortable()->searchable(),
            TextColumn::make('type')->label('Tipo')->sortable()->formatStateUsing(
                fn (string $state): string => match ($state) {
                    'revenue' => 'Ativo/Receita',
                    'expense' => 'Passivo/Despesa'
                })
        ])->filters([
            SelectFilter::make('type')->options([
                'revenue' => 'Ativo/Receita', 'expense' => 'Passivo/Despesa'
            ])->label('Tipo')
        ])->actions([
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
        ])->bulkActions([
            DeleteBulkAction::make()->label('Apagar selecionados'),
        ]);
    }

    public static function getTableQuery()
    {
        return parent::getTableQuery()->where('company_id', Auth::user()->company_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountPlanTypes::route('/'),
            'create' => Pages\CreateAccountPlanType::route('/create'),
            'edit' => Pages\EditAccountPlanType::route('/{record}/edit'),
        ];
    }
}
