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
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;

class WaliKelasResource extends Resource
{
    protected static ?string $model = WaliKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_wali')
                    ->label('Kode Wali')
                    ->required(),

                TextInput::make('nama_wali')
                    ->label('Nama Wali')
                    ->required(),

                /*  TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                    */
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_wali')->label('Kode Wali'),
                TextColumn::make('nama_wali')->label('Nama Wali'),
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
            //  'create' => Pages\CreateWaliKelas::route('/create'),
            // 'edit' => Pages\EditWaliKelas::route('/{record}/edit'),
        ];
    }
}
