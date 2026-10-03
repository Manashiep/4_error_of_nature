<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tabuse;
use Filament\Tables\Columns\TextColumn;
class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                    TextColumn::make('firstname')
    ->label('Prénom')
    ->searchable(),
    TextColumn::make('terrarian_chip_number')
    ->label('Puce Terrarienne')
    ->searchable()
    ->placeholder('-'),

                TextColumn::make('email')
                    ->searchable(),

              TextColumn::make('role')
    ->label('Rôle')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'citizen' => 'gray',
        'agent'   => 'warning',
        'admin'   => 'danger',
        default   => 'info',
    })
    ->formatStateUsing(fn (string $state): string => match ($state) {
        'citizen' => 'Citoyen',
        'agent'   => 'Agent Municipal',
        'admin'   => 'Administrateur',
        default   => $state,
    }),

                TextColumn::make('agentProfile.service_municipal')
                    ->label('Service')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
              SelectFilter::make('role')
                    ->options([
                        'citizen' => 'Citoyens',
                        'agent'   => 'Agents',
                        'admin'   => 'Administrateurs',
                    ]),
            ])
            ->actions([
                EditAction::make(),
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
