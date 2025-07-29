<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerwaliankelasResource\Pages;
use App\Filament\Resources\PerwaliankelasResource\RelationManagers;
use App\Models\Perwaliankelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Imports\PerwaliankelasImporter;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerwaliankelasResource extends Resource
{
    protected static ?string $model = Perwaliankelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_wali_kelas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_kelas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kelas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kompetensi_keahlian')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Perwaliankelas::query()->with(['kelas', 'waliKelas'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('waliKelas.nama_wali')
                    ->label('Wali Kelas')
                    ->sortable(),


                Tables\Columns\TextColumn::make('kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kompetensi_keahlian')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPerwaliankelas::route('/'),
            // 'create' => Pages\CreatePerwaliankelas::route('/create'),
            // 'edit' => Pages\EditPerwaliankelas::route('/{record}/edit'),
        ];
    }
}
