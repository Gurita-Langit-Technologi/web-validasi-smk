<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapelResource\Pages;
use App\Filament\Resources\MapelResource\RelationManagers;
use App\Models\Mapel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Imports\MapelImporter;
use Filament\Tables\Actions\ImportAction;

class MapelResource extends Resource
{
    protected static ?string $model = Mapel::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('kode_mapel')
                    ->required()
                    ->label('Kode Mapel')
                    ->maxLength(80),


                Forms\Components\Select::make('nama_guru')
                    ->required()
                    ->relationship('guru', 'nama_guru')
                    ->disabled(fn(string $operation) => $operation === 'edit')
                    ->label('Nama Guru'),

                Forms\Components\TextInput::make('nama_diklat')
                    ->required()
                    ->label('Mapel')
                    ->maxLength(80),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Mapel::query()->with(['guru'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_mapel')
                    ->label('Kode Mapel')
                    ->color('text1')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_diklat')
                    ->label('Mapel')
                    ->color('text2')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.kode_guru')
                    ->label('Kode Guru')
                    ->sortable()
                    ->color('text4')
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->label('Nama Guru')
                    ->sortable()
                    ->color('text4')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //  Tables\Actions\BulkActionGroup::make([
                // Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
    //menghilangkan s pada navigasi
    public static function getPluralLabel(): ?string
    {
        return 'Mapel';
    }
    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
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
