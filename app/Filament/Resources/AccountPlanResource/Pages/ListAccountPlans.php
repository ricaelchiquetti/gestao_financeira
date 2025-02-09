<?php

namespace App\Filament\Resources\AccountPlanResource\Pages;

use App\Filament\Resources\AccountPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountPlans extends ListRecords
{
    protected static string $resource = AccountPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
