<?php

namespace App\Filament\Resources\FinanceTransactionResource\Pages;

use App\Filament\Resources\FinanceTransactionResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListFinanceTransactions extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = FinanceTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return FinanceTransactionResource::getWidgets();
    }
}
