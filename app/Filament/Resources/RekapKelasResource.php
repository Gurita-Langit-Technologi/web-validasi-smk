<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapKelasResource\Pages;
use App\Filament\Resources\RekapKelasResource\RelationManagers;
use App\Models\RekapKelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekapKelasResource extends Resource
{
    protected static ?string $model = RekapKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_kelas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_mapel')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_guru')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_tugas')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('jumlah_selesai')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('jumlah_tanggungan')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas.jurusan')->label('Jurusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rekap_pengumpulan.status')->label('Status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wali_kelas.id_kelas')->label('Wali Kelas')
                    ->numeric()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRekapKelas::route('/'),
            'create' => Pages\CreateRekapKelas::route('/create'),
            'edit' => Pages\EditRekapKelas::route('/{record}/edit'),
            'view' => Pages\ViewRekapKelas::route('/{record}'),
        ];
    }
}
