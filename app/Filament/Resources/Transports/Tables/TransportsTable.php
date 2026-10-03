<?php

namespace App\Filament\Resources\Transports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Code')->badge()->searchable()->sortable(),
                TextColumn::make('name')->label('Ligne')->searchable()->sortable(),
                TextColumn::make('type')->label('Type')->badge(),
                TextColumn::make('frequency')->label('Fréquence'),
                TextColumn::make('status')
                    ->label('Trafic')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Interrompu' => 'danger',
                        'Perturbé' => 'warning',
                        default => 'success',
                    }),
                IconColumn::make('is_active')->label('En service')->boolean(),
            ])
            ->defaultSort('code')
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