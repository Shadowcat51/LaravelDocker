<?php

namespace App\Filament\Resources\PenjualansResource\Pages;

use App\Filament\Resources\PenjualansResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenjualans extends CreateRecord
{
    protected static string $resource = PenjualansResource::class;

    protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
