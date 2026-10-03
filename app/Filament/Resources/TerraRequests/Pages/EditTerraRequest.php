<?php

namespace App\Filament\Resources\TerraRequests\Pages;

use App\Filament\Resources\TerraRequests\TerraRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTerraRequest extends EditRecord
{
    protected static string $resource = TerraRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
