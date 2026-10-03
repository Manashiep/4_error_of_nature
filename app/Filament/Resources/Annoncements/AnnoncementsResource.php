<?php

namespace App\Filament\Resources\Annoncements;

use App\Filament\Resources\Annoncements\Pages\CreateAnnoncements;
use App\Filament\Resources\Annoncements\Pages\EditAnnoncements;
use App\Filament\Resources\Annoncements\Pages\ListAnnoncements;
use App\Filament\Resources\Annoncements\Schemas\AnnoncementsForm;
use App\Filament\Resources\Annoncements\Tables\AnnoncementsTable;
use App\Models\Annoncements;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AnnoncementsResource extends Resource
{
    protected static ?string $model = Annoncements::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'annonce';

    protected static ?string $pluralModelLabel = 'annonces';

    public static function form(Schema $schema): Schema
    {
        return AnnoncementsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnnoncementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnnoncements::route('/'),
            'create' => CreateAnnoncements::route('/create'),
            'edit' => EditAnnoncements::route('/{record}/edit'),
        ];
    }
}