<?php

namespace App\Filament\Resources\FinanceTransactionResource\Pages;

use App\Filament\Resources\FinanceTransactionResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFinanceTransaction extends CreateRecord
{
    protected static string $resource = FinanceTransactionResource::class;

    /** @inheritDoc */
    protected function handleRecordCreation(array $data): Model
    {
        $count = count($data['installments_fields']);
        $index = 0;

        $record = array_map(function ($fields) use (&$data, &$index, $count) {
            $index++;
            $fields = array_merge($fields, $data);
            $fields['description'] .= " $index/$count";

            $record = new ($this->getModel())($fields);
            if (static::getResource()::isScopedToTenant() && ($tenant = Filament::getTenant())) {
                return $this->associateRecordWithTenant($record, $tenant);
            }
            $record->save();

            return $record;
        }, $data['installments_fields']);

        return array_shift($record);
    }
}
