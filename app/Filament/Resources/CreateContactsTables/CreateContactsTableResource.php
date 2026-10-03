<?php

namespace App\Filament\Resources\CreateContactsTables;

use App\Filament\Resources\CreateContactsTables\Pages\CreateCreateContactsTable;
use App\Filament\Resources\CreateContactsTables\Pages\EditCreateContactsTable;
use App\Filament\Resources\CreateContactsTables\Pages\ListCreateContactsTables;
use App\Filament\Resources\CreateContactsTables\Schemas\CreateContactsTableForm;
use App\Filament\Resources\CreateContactsTables\Tables\CreateContactsTablesTable;
use App\Models\CreateContactsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CreateContactsTableResource extends Resource
{
    protected static ?string $model = CreateContactsTable::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Contact/Service Citoyen';

    public static function form(Schema $schema): Schema
    {
        return CreateContactsTableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreateContactsTablesTable::configure($table);
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
            'index' => ListCreateContactsTables::route('/'),
            'create' => CreateCreateContactsTable::route('/create'),
            'edit' => EditCreateContactsTable::route('/{record}/edit'),
        ];
    }
}
