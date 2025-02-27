<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontFamily;
use App\Filament\Imports\GuruImporter;
use Filament\Tables\Actions\ImportAction;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;
    protected static ?string $navigationBadgeTooltip = 'Jumlah Guru';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_guru')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_guru')
                    ->required()
                    ->maxLength(80),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_guru')
                    ->color('text1')
                    ->icon('heroicon-o-bookmark-square')
                    ->fontFamily(FontFamily::Mono)
                    ->copyable()
                    ->copyMessage('Nisn copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-o-envelope')
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_guru')
                    ->color('text2')
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
                    ->importer(GuruImporter::class)


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
        return 'Guru';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::$model::count();

        return 'info';
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
