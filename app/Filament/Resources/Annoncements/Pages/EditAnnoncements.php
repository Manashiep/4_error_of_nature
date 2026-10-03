<?php

namespace App\Filament\Resources\Annoncements\Pages;

use App\Filament\Resources\Annoncements\AnnoncementsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAnnoncements extends EditRecord
{
    protected static string $resource = AnnoncementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
