<?php

namespace App\Filament\Resources\DreResource\Pages;

use App\Filament\Resources\DreResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListDres extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = DreResource::class;

    protected function getHeaderWidgets(): array
    {
        return DreResource::getWidgets();
    }
}
