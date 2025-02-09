<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntityResource\Pages;
use App\Models\Entity;
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

class EntityResource extends Resource
{
    protected static ?string $model = Entity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Minhas Empresas';

    protected static ?string $label = 'Fornecedor/Cliente';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->label('Nome')->columnSpanFull(),
            Select::make('type')->options([
                'supplier' => 'Fornecedor',
                'customer' => 'Cliente',
            ])->required()->label('Tipo')->columnSpanFull(),
            
            TextInput::make('email')->email()->label('E-mail'),
            TextInput::make('phone')->tel()->label('Telefone'),
            TextInput::make('address')->label('Endereço')->columnSpanFull(),
            Hidden::make('company_id')->default(Auth::user()->company_id)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable()->label('Nome'),
            TextColumn::make('type')->sortable()->label('Type'),
            TextColumn::make('email')->sortable()->searchable()->label('E-mail'),
            TextColumn::make('phone')->sortable()->searchable()->label('Phone'),
        ])->filters([
            SelectFilter::make('type')->options([
                'supplier' => 'Fornecedor',
                'customer' => 'Cliente',
            ])
        ])->actions([
            EditAction::make()->label(''),
            DeleteAction::make()->label(''),
        ])->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getTableQuery()
    {
        return parent::getTableQuery()->where('company_id', Auth::user()->company_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntities::route('/'),
            'create' => Pages\CreateEntity::route('/create'),
            'edit' => Pages\EditEntity::route('/{record}/edit'),
        ];
    }
}
