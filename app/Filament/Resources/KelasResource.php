<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Imports\KelasImporter;
use Filament\Tables\Actions\ImportAction;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nama_kelas')
                    ->required()
                    ->maxLength(10),
                Forms\Components\Select::make('tingkat_kelas')
                    ->searchable()
                    ->options(Kelas::all()->pluck('tingkat_kelas'))
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('kompetensi_keahlian')
                    ->options(Kelas::all()->pluck('kompetensi_keahlian'))
                    ->searchable()
                    ->preload()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kelas')
                    ->color('text1')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_kelas')
                    ->color('text2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tingkat_kelas')
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kompetensi_keahlian')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(KelasImporter::class)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
