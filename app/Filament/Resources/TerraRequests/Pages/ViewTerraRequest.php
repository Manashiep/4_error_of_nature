<?php

namespace App\Filament\Resources\TerraRequests\Pages;

use App\Filament\Resources\TerraRequests\TerraRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTerraRequest extends ViewRecord
{
    protected static string $resource = TerraRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
