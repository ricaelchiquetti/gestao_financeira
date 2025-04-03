<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListFlows extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = FlowResource::class;

    protected function getHeaderWidgets(): array
    {
        return FlowResource::getWidgets();
    }
}
