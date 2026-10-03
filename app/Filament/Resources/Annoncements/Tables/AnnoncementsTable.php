<?php

namespace App\Filament\Resources\Annoncements\Tables;


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
class AnnoncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Illustration')
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

                TextColumn::make('user.name')
                    ->label('Auteur')
                    ->placeholder('Administration'),
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
