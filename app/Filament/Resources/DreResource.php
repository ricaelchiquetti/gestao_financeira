<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Filters\CompanyIdFilter;
use App\Filament\Resources\DreResource\Pages;
use App\Filament\Resources\DreResource\Widgets\DreStats;
use App\Models\AccountPlan;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Grouping\Group;

class DreResource extends Resource
{
    protected static ?string $model = AccountPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'DRE';
    protected static ?string $pluralLabel = 'DRE';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->sortable()->label('Plano'),
                ...self::generateMonthlyColumns()
            ])
            ->filters([
                Filter::make('year')
                    ->form([
                        TextInput::make('year')
                            ->label('Ano')
                            ->numeric()
                            ->minLength(4)
                            ->maxLength(4)
                            ->required(),
                    ])
                    ->query(function ($query, $data) {
                        try {
                            $parsedYear = Carbon::createFromFormat('Y', $data['year']);
                            if ($parsedYear) {
                                return $query->whereHas('financeTransactions', function (Builder $query) use ($parsedYear) {
                                    $query->whereYear('transaction_date', $parsedYear->year);
                                });
                            }
                        } catch (\Exception $e) {
                            return $query;
                        }
                    }),
                CompanyIdFilter::make()
            ], layout: FiltersLayout::AboveContent)
            ->defaultGroup(Group::make('AccountPlanType.type')->label('')->getTitleFromRecordUsing(
                function (AccountPlan $accountPlan): string {
                    return $accountPlan->AccountPlanType->type === 'revenue' ? 'Receita Operacional Bruta' : 'Custos/Despesas Variáveis';
                })
        );
    }

    protected static function generateMonthlyColumns(): array
    {
        $columns = [];
        $months = [
            'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
        ];

        foreach ($months as $num => $month) {
            $columns[] = TextColumn::make($month)
                ->label($month)
                ->getStateUsing(function ($record, $livewire) use ($num) {
                    $year = $livewire->tableFilters['year'] ?? Carbon::now()->year;

                    $monthValue = $record->financeTransactions()
                                        ->whereMonth('transaction_date', $num + 1)
                                        ->whereYear('transaction_date', $year)
                                        ->sum('value');
                    return 'R$ ' . number_format($monthValue, 2, ',', '.');
                });
        }

        return $columns;
    }

    public static function getWidgets(): array
    {
        return [
            DreStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDres::route('/'),
        ];
    }
}
