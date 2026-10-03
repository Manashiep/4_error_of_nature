<?php

namespace App\Filament\Resources\Alerts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Contenu de l'alerte")
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('level')
                            ->label('Niveau')
                            ->options([
                                'info' => 'Information',
                                'important' => 'Important',
                                'alerte' => 'Alerte / Urgence',
                            ])
                            ->required()
                            ->default('info'),

                        Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'Ville' => 'Ville',
                                'Eau' => 'Eau / Inondation',
                                'Santé' => 'Santé / Canicule',
                                'Sécurité' => 'Sécurité',
                                'Transport' => 'Transport',
                                'Travaux' => 'Travaux',
                            ])
                            ->required()
                            ->default('Ville'),

                        Textarea::make('summary')
                            ->label("Message court (affiché sur l'accueil)")
                            ->rows(2)
                            ->maxLength(300)
                            ->columnSpanFull(),

                        Textarea::make('body')
                            ->label('Détails et consignes (que faire)')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Diffusion')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Alerte active')
                            ->default(true),

                        DateTimePicker::make('published_at')
                            ->label('Diffusée à partir de')
                            ->default(now()),

                        DateTimePicker::make('expires_at')
                            ->label("Expire le (vide = jusqu'à désactivation)"),
                    ])
                    ->columns(3),
            ]);
    }
}