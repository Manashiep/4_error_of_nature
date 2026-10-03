<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

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
                            ->afterStateUpdated(function ($set, ?string $state) {
                                if (blank($state)) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

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

                Section::make('Médias & Contacts')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Illustration / Photo du service')
                            ->image()
                            ->directory('services')
                            ->visibility('public'),

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
