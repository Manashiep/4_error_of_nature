<?php

namespace App\Filament\Resources\TerraRequests;

use App\Filament\Resources\TerraRequests\Pages\CreateTerraRequest;
use App\Filament\Resources\TerraRequests\Pages\EditTerraRequest;
use App\Filament\Resources\TerraRequests\Pages\ListTerraRequests;
use App\Filament\Resources\TerraRequests\Pages\ViewTerraRequest;
use App\Filament\Resources\TerraRequests\Schemas\TerraRequestForm;
use App\Filament\Resources\TerraRequests\Schemas\TerraRequestInfolist;
use App\Filament\Resources\TerraRequests\Tables\TerraRequestsTable;
use App\Models\TerraRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TerraRequestResource extends Resource
{
    protected static ?string $model = TerraRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Demande Terrarienne';

    public static function form(Schema $schema): Schema
    {
        return TerraRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TerraRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TerraRequestsTable::configure($table);
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
            'index' => ListTerraRequests::route('/'),
            'create' => CreateTerraRequest::route('/create'),
            'view' => ViewTerraRequest::route('/{record}'),
            'edit' => EditTerraRequest::route('/{record}/edit'),
        ];
    }
}
