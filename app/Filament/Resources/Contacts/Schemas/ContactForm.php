<?php

namespace App\Filament\Resources\Contacts\Schemas;


use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
               Section::make('Détails du Message')
                    ->schema([
                        Select::make('type')
                            ->label('Type de demande')
                            ->options([
                                'general' => 'Question Générale (Page Contact)',
                                'service' => 'Demande adressée à un Service',
                            ])
                            ->required(),

                        Select::make('service_id')
                            ->label('Service concerné')
                            ->relationship('service', 'name')
                            ->placeholder('Aucun service spécifique')
                            ->visible(fn ($get) => $get('type') === 'service'),

                        TextInput::make('name')
                            ->label('Nom complet')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Téléphone'),

                        TextInput::make('subject')
                            ->label('Sujet')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('message')
                            ->label('Contenu du message')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Traitement Administratif')
                    ->schema([
                        Select::make('status')
                            ->label('Statut du traitement')
                            ->options([
                                'nouveau' => 'Nouveau',
                                'en_cours' => 'En cours de traitement',
                                'traite' => 'Traité / Répondu',
                                'archive' => 'Archivé',
                            ])
                            ->required()
                            ->default('nouveau'),

                        Textarea::make('admin_notes')
                            ->label('Notes internes de l\'agent')
                            ->placeholder('Ajoutez des précisions sur la réponse apportée...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
