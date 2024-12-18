<?php

namespace App\Filament\Resources\PenjualansResource\Pages;

use App\Filament\Resources\PenjualansResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
