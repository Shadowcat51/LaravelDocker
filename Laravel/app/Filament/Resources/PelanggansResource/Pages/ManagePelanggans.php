<?php

namespace App\Filament\Resources\PelanggansResource\Pages;

use App\Filament\Resources\PelanggansResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePelanggans extends ManageRecords
{
    protected static string $resource = PelanggansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
