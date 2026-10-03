<?php

namespace App\Filament\Resources\Annoncements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnoncementsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Rédaction de l'annonce")
                ->schema([
                    TextInput::make('title')
                        ->label("Titre de l'actualité")
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Select::make('category')
                        ->label('Catégorie')
                        ->options([
                            'Général'  => 'Information Générale',
                            'Travaux'  => 'Voirie & Travaux',
                            'Alerte'   => 'Alerte / Urgence',
                            'Culture'  => 'Culture & Événements',
                            'Services' => 'Changement de Service',
                        ])
                        ->required()
                        ->default('Général'),

                    Toggle::make('is_published')
                        ->label('Publier')
                        ->default(true),

                    DateTimePicker::make('published_at')
                        ->label('Date de publication')
                        ->default(now()),

                    Textarea::make('content')
                        ->label("Contenu de l'annonce")
                        ->required()
                        ->rows(8)
                        ->columnSpanFull(),

                    FileUpload::make('image_path')
                        ->label("Image d'illustration")
                        ->image()
                        ->disk('public')
                        ->directory('announcements')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}