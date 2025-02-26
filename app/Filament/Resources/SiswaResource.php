<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use App\Filament\Imports\SiswaImporter;
use Filament\Tables\Actions\ImportAction;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationLabel = 'Siswa'; //label tanpa s

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nisn')
                    ->required()
                    ->maxLength(12),
                Forms\Components\TextInput::make('nama_siswa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_kelas')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('jurusan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('nisn')
                    ->color('text1')
                    ->icon('heroicon-o-chevron-double-right')
                    ->fontFamily(FontFamily::Mono)
                    ->copyable()
                    ->copyMessage('Nisn copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_siswa')
                    ->color('text2')
                    ->weight(FontWeight::Medium)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan')
                    ->color('text4')
                    ->searchable(),

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
                    ->importer(SiswaImporter::class)


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
        return 'Siswa';
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
