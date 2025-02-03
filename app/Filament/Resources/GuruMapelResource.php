<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruMapelResource\Pages;
use App\Filament\Resources\GuruMapelResource\RelationManagers;
use App\Models\GuruMapel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuruMapelResource extends Resource
{
    protected static ?string $model = GuruMapel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Nip')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Nama Guru')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Mapel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Kelas')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListGuruMapels::route('/'),
            'create' => Pages\CreateGuruMapel::route('/create'),
            'edit' => Pages\EditGuruMapel::route('/{record}/edit'),
        ];
    }
}
