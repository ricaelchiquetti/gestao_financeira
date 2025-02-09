<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Minha Empresa';

    protected static ?string $label = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nome')->required()->maxLength(255),
            TextInput::make('email')->label('E-mail')->required()->email()->unique(User::class, 'email', fn ($query) => $query->ignore(auth()->id())),
            TextInput::make('password')->label('Senha')->columns(2)
                ->required()
                ->minLength(8)
                ->same('password_confirmation')
                ->password()
                ->revealable(),
            Toggle::make('admin')->label('Administrador')->default(false)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Name')->sortable()->searchable(),
            TextColumn::make('email')->label('Email')->sortable()->searchable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
