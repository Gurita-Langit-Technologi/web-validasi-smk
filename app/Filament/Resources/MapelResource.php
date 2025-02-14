<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mapel;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Imports\MapelImporter;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MapelResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MapelResource\RelationManagers;

class MapelResource extends Resource
{
    protected static ?string $model = Mapel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_mapel')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tema_tugas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_tugas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_selesai')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_tanggungan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('deskripsi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_mapel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tema_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_tugas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_selesai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_tanggungan')
                    ->numeric()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(MapelImporter::class)
            ]);
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
            'index' => Pages\ListMapels::route('/'),
            'create' => Pages\CreateMapel::route('/create'),
            'edit' => Pages\EditMapel::route('/{record}/edit'),
        ];
    }
}
