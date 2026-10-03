<?php

namespace App\Filament\Resources\TerraRequests\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
class TerraRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Statut du traitement')
                    ->options([
                        'En attente'     => 'En attente',
                        'En cours' => 'En cours',
                        'Terminé'   => 'Terminé',
                    ])
                    ->required(),

            ]);
    }
}
