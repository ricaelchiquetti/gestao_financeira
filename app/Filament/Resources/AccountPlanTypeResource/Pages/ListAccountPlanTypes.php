<?php

namespace App\Filament\Resources\AccountPlanTypeResource\Pages;

use App\Filament\Resources\AccountPlanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountPlanTypes extends ListRecords
{
    protected static string $resource = AccountPlanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
