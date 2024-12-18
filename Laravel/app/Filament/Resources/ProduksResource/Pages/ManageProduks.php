<?php

namespace App\Filament\Resources\ProduksResource\Pages;

use App\Filament\Resources\ProduksResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProduks extends ManageRecords
{
    protected static string $resource = ProduksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
