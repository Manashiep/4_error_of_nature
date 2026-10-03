<?php

namespace App\Filament\Resources\TerraRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TerraRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('requester_name')
                    ->label('Demandeur')
                    ->searchable(),

                TextColumn::make('message_public')
                    ->label('Consigne')
                    ->wrap() // Affiche tout le texte du message clairement
                    ->searchable(),

                TextColumn::make('difficulty')
                    ->label('Difficulté')
                    ->badge()
                    ->color(fn (?string $state): string => match (mb_strtolower((string) $state)) {
                        'facile'    => 'success',
                        'moyenne'     => 'warning',
                        'difficile' => 'danger',

                    }),

                TextColumn::make('xp_total')
                    ->label('XP Total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('wave_number')
                    ->label('Vague')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'En attente'     => 'gray',
                        'En cours' => 'warning',
                        'Terminé'   => 'success',
                        default       => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('wave_number')
                    ->label('Filtrer par Vague'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
