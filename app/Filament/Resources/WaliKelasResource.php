<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaliKelasResource\Pages;
use App\Filament\Resources\WaliKelasResource\RelationManagers;
use App\Models\WaliKelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WaliKelasResource extends Resource
{
    protected static ?string $model = WaliKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\TextInput::make('nama_wali')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('id_kelas')
                    ->label('Kelas')
                    ->relationship('kelas', 'kode_kelas') // pastikan field 'kode_kelas' ada di tabel kelas
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->relationship('kelas', 'nama_kelas') // pastikan field 'kode_kelas' ada di tabel kelas
                    ->searchable()
                    ->preload()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                WaliKelas::query()->with(['kelas'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('guru.kode_guru')
                    ->label('Kode Guru')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_wali')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.tingkat_kelas')
                    ->label('Tingkat Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.kompetensi_keahlian')
                    ->label('Kompetensi Keahlian')
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
            'index' => Pages\ListWaliKelas::route('/'),
            'create' => Pages\CreateWaliKelas::route('/create'),
            'edit' => Pages\EditWaliKelas::route('/{record}/edit'),
        ];
    }
}
