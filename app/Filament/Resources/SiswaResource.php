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
use Filament\Tables\Filters\SelectFilter;






class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationLabel = 'Siswa'; //label tanpa s
    protected static ?string $navigationBadgeTooltip = 'Jumlah Siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_induk')
                    ->label('No Induk')
                    ->maxLength(12)
                    ->required(),
                Forms\Components\TextInput::make('nama_siswa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->relationship('kelas', 'nama_kelas') // pastikan field 'kode_kelas' ada di tabel kelas
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'kode_kelas') // pastikan field 'kode_kelas' ada di tabel kelas
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Siswa::query()->with(['kelas'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('no_induk')
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

                Tables\Columns\TextColumn::make('kelas.tingkat_kelas')
                    ->label('Tingkat Kelas')
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Nama Kelas')
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.kompetensi_keahlian')
                    ->label('Kompt Keahlian')
                    ->color('text3')
                    ->searchable(),




            ])
            ->filters([])
            ->actions([
                //  Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::$model::count();

        return 'text2';
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            //  'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
