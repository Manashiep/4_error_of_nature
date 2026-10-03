<?php

namespace App\Filament\Resources\Reports\Tables;


use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->limit(25),

                TextColumn::make('service.name')
                    ->label('Service')
                    ->placeholder('Non assigné')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Citoyen')
                    ->placeholder('Anonyme'),

                SelectColumn::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'in_progress' => 'En cours',
                        'resolved' => 'Résolu',
                        'rejected' => 'Rejeté',
                    ]),

                ImageColumn::make('image_path')
                    ->label('Photo')
                    ->circular(),

                TextColumn::make('created_at')
                    ->label('Date d\'envoi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtrer par statut')
                    ->options([
                        'pending' => 'En attente',
                        'in_progress' => 'En cours',
                        'resolved' => 'Résolu',
                        'rejected' => 'Rejeté',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),

            ])
            ->filters([
                //
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
