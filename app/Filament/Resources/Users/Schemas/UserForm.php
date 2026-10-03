<?php

namespace App\Filament\Resources\Users\Schemas;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Components\TextInput;
class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             Section::make('Informations Générales')
    ->schema([
        TextInput::make('name')
            ->label('Nom complet')
            ->required(),
            TextInput::make('firstname')
    ->label('Prénom')
    ->required(),
   TextInput::make('terrarian_chip_number')
    ->label('N° Puce Terrarienne')
    ->placeholder('ex: TN-8942-X')
    ->required(),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),

                   TextInput::make('password')
                        ->label('Mot de passe')
                        ->password()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),

                    Select::make('role')
                        ->label('Rôle / Type de profil')
                        ->options([
                            'citizen' => 'Citoyen',
                            'agent'   => 'Agent Municipal',
                            'admin'   => 'Administrateur',
                        ])
                        ->default('citizen')
                        ->live() // Permet de réagir en direct lors du changement
                        ->required(),

                    // Section pour le profil Agent (auto-enregistrée via la relation)
                    Section::make('Informations Service Municipal')
                        ->relationship('agentProfile')
                        ->schema([
                            TextInput::make('service_municipal')
                                ->label('Service Municipal / Direction')
                                ->placeholder('ex: Service Voirie, Direction des Services Techniques'),
                           TextInput::make('matricule')
                                ->label('Matricule professionnel'),
                        ])
                        ->visible(fn ($get) => $get('role') === 'agent')
                ])->columns(2),

            ]);
    }
}
