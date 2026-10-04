<?php

namespace App\Filament\Resources\Annoncements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnnoncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Illustration')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(35),

                TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // Nom de l'auteur / agent créateur
                TextColumn::make('user.name')
                    ->label('Auteur')
                    ->placeholder('Administration')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Filtrer par catégorie')
                    ->options([
                        'Général' => 'Information Générale',
                        'Travaux' => 'Voirie & Travaux',
                        'Alerte' => 'Alerte / Urgence',
                        'Culture' => 'Culture & Événements',
                        'Services' => 'Changement de Service',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
