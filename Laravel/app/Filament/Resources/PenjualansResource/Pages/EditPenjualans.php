<?php

namespace App\Filament\Resources\PenjualansResource\Pages;

use App\Filament\Resources\PenjualansResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenjualans extends EditRecord
{
    protected static string $resource = PenjualansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
