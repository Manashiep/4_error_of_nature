<?php

namespace App\Filament\Resources\Contacts\Schemas;


use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Get;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use Illuminate\Support\Str;
class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Demande reçue de l\'habitant')
            ->schema([
                TextInput::make('name')
                    ->label('Nom du citoyen')
                    ->disabled(),

                TextInput::make('email')
                    ->label('Adresse e-mail')
                    ->disabled(),

                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Service concerné')
                    ->disabled(),

                DateTimePicker::make('requested_at')
                    ->label('Créneau souhaité par l’habitant')
                    ->disabled(),

                Textarea::make('message')
                    ->label('Motif / Message')
                    ->disabled()
                    ->columnSpanFull(),
            ])->columns(2),

        // 2. ACTION DE L'AGENT : Décision uniquement (Visible si type === 'service')
        Section::make('Traitement du Rendez-vous')
            ->visible(fn (callable $get): bool => $get('type') === 'service')
            ->schema([
                Select::make('status')
                    ->label('Décision')
                    ->options([
                        'nouveau' => 'En attente',
                        'traite'  => 'Accepter le rendez-vous',
                        'archive' => 'Refuser le rendez-vous',
                    ])
                    ->required()
                    ->live(),

                // Si Accepté : choix/confirmation de l'horaire
                DateTimePicker::make('confirmed_at')
                    ->label('Fixer / Confirmer l\'horaire du RDV')
                    ->required(fn (callable $get): bool => $get('status') === 'traite')
                    ->visible(fn (callable $get): bool => $get('status') === 'traite'),

                // Si Refusé : saisie du motif
               Textarea::make('rejection_reason')
                    ->label('Motif du refus (expliqué au citoyen)')
                    ->required(fn (callable $get): bool => $get('status') === 'archive')
                    ->visible(fn (callable $get): bool => $get('status') === 'archive')
                    ->columnSpanFull(),
            ])->columns(2),

            ]);
    }
}
