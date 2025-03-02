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
use Filament\Support\Enums\FontFamily;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_kelas')
                    ->required()
                    ->maxLength(10),
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
                Tables\Columns\TextColumn::make('kode_kelas')
                    ->icon('heroicon-o-book-open')
                    ->fontFamily(FontFamily::Mono)
                    ->color('text1')

                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->color('text2')

                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan')
                    ->color('text4')

                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([])
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
