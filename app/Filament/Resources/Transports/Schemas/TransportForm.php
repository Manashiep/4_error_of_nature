<?php

namespace App\Filament\Resources\Transports\Schemas;

use Filament\Schemas\Schema;
use App\Filament\Resources\TransportLineResource\Pages;
use App\Models\TransportLine;
use Filament\Forms\Components\Repeater;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
class TransportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Ligne')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom de la ligne')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('code')
                            ->label('Code / Numéro (ex: L-01)')
                            ->required()
                            ->maxLength(50),

                        Select::make('type')
                            ->label('Type de transport')
                            ->options([
                                'Bus' => 'Bus municipal',
                                'Navette' => 'Navette côtière / urbaine',
                                'Scolaire' => 'Transport scolaire',
                            ])
                            ->required()
                            ->default('Bus'),

                        TextInput::make('frequency')
                            ->label('Fréquence globale (Aperçu)')
                            ->placeholder('Ex: Toutes les 20 min'),

                        Textarea::make('route_description')
                            ->label('Trajet et arrêts principaux')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Grille des Horaires')
                    ->schema([
                        Repeater::make('schedules')
                            ->label('Plages horaires de passage')
                            ->schema([
                                Select::make('days')
                                    ->label('Jours concernés')
                                    ->options([
                                        'Lundi - Vendredi' => 'Lundi au Vendredi',
                                        'Samedi' => 'Samedi',
                                        'Dimanche & Fériés' => 'Dimanche & Jours fériés',
                                        'Tous les jours' => 'Tous les jours',
                                    ])
                                    ->required(),

                                TextInput::make('hours')
                                    ->label('Horaires / Fréquence pour ces jours')
                                    ->placeholder('Ex: 06:00, 06:30, puis toutes les 15 min jusqu’à 21:00')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->helperText('Ajoutez les différentes plages horaires selon les jours de la semaine.'),
                    ]),

                Section::make('État du trafic en temps réel')
                    ->schema([
                        Select::make('status')
                            ->label('Statut du trafic')
                            ->options([
                                'Normal' => 'Trafic normal',
                                'Perturbé' => 'Trafic perturbé (retards)',
                                'Interrompu' => 'Ligne interrompue',
                            ])
                            ->required()
                            ->default('Normal'),

                        Toggle::make('is_active')
                            ->label('Ligne active en service')
                            ->default(true),

                        Textarea::make('status_message')
                            ->label('Message d\'information / Détail de la perturbation')
                            ->placeholder('Ex: Retards de 15 minutes suite à des travaux sur l\'avenue principale.')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
