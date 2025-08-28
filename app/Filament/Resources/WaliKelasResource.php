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
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Guru;
use App\Filament\Imports\WaliKelasImporter;
use Filament\Tables\Actions\ImportAction;

class WaliKelasResource extends Resource
{
    protected static ?string $model = WaliKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_guru')
                    ->label('Kode Guru')
                    ->relationship('guru', 'kode_guru')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live() // Aktifkan live mode agar perubahan langsung terdeteksi
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $guruId = $get('id_guru');
                        if ($guruId) {
                            $guru = Guru::find($guruId); // Ambil data guru dari database
                            if ($guru) {
                                $set('kode_wali', $guru->kode_guru); // Isi kode_wali dengan kode_guru
                                $set('nama_wali', $guru->nama_guru); // Isi nama_wali dengan nama_guru
                            }
                        }
                    }),

                Forms\Components\Select::make('id_kelas')
                    ->label('Pilih Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $kelas = \App\Models\Kelas::find($state);
                            $set('nama_kelas', $kelas?->nama_kelas);
                        }
                    }),

                Forms\Components\TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->disabled(),

                Forms\Components\TextInput::make('kode_wali')
                    ->label('Kode Wali')
                    ->required()
                    ->dehydrated(true) // Pastikan field ini dikirim saat form disubmit
                    ->reactive() // Ini penting agar nilai bisa diisi dari select
                    ->live(),

                Forms\Components\TextInput::make('nama_wali')
                    ->label('Nama Wali')
                    ->required()
                    ->dehydrated(true) // Pastikan field ini dikirim saat form disubmit
                    ->reactive() // Ini penting agar nilai bisa diisi dari select
                    ->live(),

                Forms\Components\TextInput::make('role')
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                WaliKelas::query()->with(['kelas', 'guru'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Nama Kelas')
                    ->color('text1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->label('Nama Guru')
                    ->color('text2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_wali')
                    ->color('text3')
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
                //    Tables\Actions\DeleteBulkAction::make(),
                //  ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(WaliKelasImporter::class)
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
            'index' => Pages\ListWaliKelas::route('/'),
            'create' => Pages\CreateWaliKelas::route('/create'),
            'edit' => Pages\EditWaliKelas::route('/{record}/edit'),
        ];
    }
}
