<?php

namespace App\Filament\Resources\Transports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class TransportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Ligne')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color('info'),

                TextColumn::make('frequency')
                    ->label('Fréquence'),

                TextColumn::make('status')
                    ->label('État')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Normal' => 'success',
                        'Perturbé' => 'warning',
                        'Interrompu' => 'danger',
                    }),

                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->sortable(),

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
