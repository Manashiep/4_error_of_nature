<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'general' => 'Général',
                        'service' => 'Service',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'info',
                        'service' => 'warning',
                    }),

                TextColumn::make('service.name')
                    ->label('Service ciblé')
                    ->placeholder('Global / Mairie'),

                TextColumn::make('name')
                    ->label('Expéditeur')
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Sujet')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'nouveau' => 'danger',
                        'en_cours' => 'warning',
                        'traite' => 'success',
                        'archive' => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'general' => 'Questions Générales',
                        'service' => 'Demandes de Services',
                    ]),
               SelectFilter::make('status')
                    ->options([
                        'nouveau' => 'Nouveaux',
                        'en_cours' => 'En cours',
                        'traite' => 'Traités',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
