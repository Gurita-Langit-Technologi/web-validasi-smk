<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\GuruMapel;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Imports\GuruMapelImporter;
use App\Filament\Resources\GuruMapelResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GuruMapelResource\RelationManagers;

class GuruMapelResource extends Resource
{
    protected static ?string $model = GuruMapel::class;

    protected static ?string $navigationIcon = 'heroicon-c-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Nip')
                    ->maxLength(255),
                Forms\Components\TextInput::make('Nama Guru')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('Mapel')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('Kelas')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Nip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Nama Guru')
                    ->searchable()
                    ->color('primary'),

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
                    ->importer(GuruMapelImporter::class)
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
