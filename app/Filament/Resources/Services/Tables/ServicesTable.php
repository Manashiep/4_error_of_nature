<?php

namespace App\Filament\Resources\Services\Tables;

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
class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Illustration')
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label('Nom du service')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('Consultations (Clics)')
                    ->sortable()
                    ->numeric(),

                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('Épinglé')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options([
                        'Général' => 'Général',
                        'Voirie & Infrastructure' => 'Voirie & Infrastructure',
                        'Santé & Social' => 'Santé & Social',
                        'État Civil & Administratif' => 'État Civil & Administratif',
                        'Environnement & Propreté' => 'Environnement & Propreté',
                        'Sécurité' => 'Sécurité',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Statut (Actif / Maintenance)'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
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
