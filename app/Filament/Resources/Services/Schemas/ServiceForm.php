<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Service;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations Générales')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom du service')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Identifiant URL (Slug)')
                            ->required()
                            ->unique(Service::class, 'slug', ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'Général' => 'Général',
                                'Voirie & Infrastructure' => 'Voirie & Infrastructure',
                                'Transport' => 'Transport',
                                'Santé & Social' => 'Santé & Social',
                                'État Civil & Administratif' => 'État Civil & Administratif',
                                'Environnement & Propreté' => 'Environnement & Propreté',
                                'Sécurité' => 'Sécurité',
                            ])
                            ->default('Général')
                            ->required(),

                        Textarea::make('short_description')
                            ->label('Aperçu court (Carte)')
                            ->rows(2)
                            ->maxLength(255),

                        RichEditor::make('description')
                            ->label('Description complète du service')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Localisation & Horaires (Req. F39)')
                    ->schema([
                        TextInput::make('address')
                            ->label('Adresse physique')
                            ->placeholder('ex: 12 Rue de la Mairie, Nova Terra')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('opening_hours')
                            ->label('Horaires d’ouverture')
                            ->placeholder("ex: Lundi - Vendredi : 8h00 - 16h30\nSamedi : 8h00 - 12h00")
                            ->rows(3),

                        TextInput::make('latitude')
                            ->label('Latitude GPS (Optionnel)')
                            ->placeholder('ex: -11.7022'),

                        TextInput::make('longitude')
                            ->label('Longitude GPS (Optionnel)')
                            ->placeholder('ex: 43.2551'),
                    ])->columns(3),

                Section::make('Médias & Contacts')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Image du service')
                            ->image()
                            ->disk('public')
                            ->directory('services')
                            ->visibility('public')
                            ->preserveFilenames(),

                        TextInput::make('contact_email')
                            ->label('Courriel de contact')
                            ->email(),

                        TextInput::make('contact_phone')
                            ->label('Téléphone de contact')
                            ->tel(),
                    ])->columns(3),

                Section::make('Statut & Visibilité (Exigences API)')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Service Actif')
                            ->helperText('Décocher pour passer en mode maintenance / panne (Req. F38)')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Mettre en avant')
                            ->helperText('Épingler en haut de la liste sur le portail citoyen (Req. F28)')
                            ->default(false),
                    ])->columns(2),
            ]);
    }
}
