<?php

namespace App\Filament\Resources\Annoncements\Schemas;

use Filament\Schemas\Schema;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Forms\Components\FileUpload;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
class AnnoncementsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rédaction de l\'Annonce')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre de l\'actualité')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'Général' => 'Information Générale',
                                'Travaux' => 'Voirie & Travaux',
                                'Alerte' => 'Alerte / Urgence',
                                'Culture' => 'Culture & Événements',
                                'Services' => 'Changement de Service',
                            ])
                            ->required()
                            ->default('Général'),

                        Toggle::make('is_published')
                            ->label('Publier immédiatement')
                            ->default(true),

                        DateTimePicker::make('published_at')
                            ->label('Date de publication')
                            ->default(now()),

                        Textarea::make('content')
                            ->label('Contenu de l\'annonce')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->label('Image d\'illustration')
                            ->image()
                            ->directory('announcements')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
