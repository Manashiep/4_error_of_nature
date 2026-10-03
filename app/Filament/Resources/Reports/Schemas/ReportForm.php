<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Schemas\Schema;
use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Forms\Components\FileUpload;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Signalement')
                    ->schema([
                        TextInput::make('reference')
                            ->label('Référence du dossier')
                            ->disabled()
                            ->dehydrated(false), // Ne pas essayer de sauvegarder si désactivé

                        Select::make('status')
                            ->label('Statut de prise en charge')
                            ->options([
                                'pending' => 'En attente',
                                'in_progress' => 'En cours de traitement',
                                'resolved' => 'Résolu',
                                'rejected' => 'Rejeté',
                            ])
                            ->required(),

                        TextInput::make('title')
                            ->label('Titre du signalement')
                            ->required(),

                        TextInput::make('category')
                            ->label('Catégorie')
                            ->placeholder('Ex: Voirie, Éclairage, Propreté...'),

                        Select::make('service_id')
                            ->label('Service Attribué')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        TextInput::make('location')
                            ->label('Localisation / Adresse'),

                        Textarea::make('description')
                            ->label('Description détaillée')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->label('Photo preuve / Illustration')
                            ->image()
                            ->directory('reports')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
