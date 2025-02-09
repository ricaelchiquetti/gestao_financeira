<?php

namespace App\Filament\Resources\AccountPlanTypeResource\Pages;

use App\Filament\Resources\AccountPlanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountPlanType extends EditRecord
{
    protected static string $resource = AccountPlanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
