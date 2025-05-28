<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapKelasResource\Pages;
use App\Filament\Resources\RekapKelasResource\RelationManagers;
use App\Models\Kelas;
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
                Forms\Components\TextInput::make('id_wali_kelas')
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
            ->query(
                RekapKelas::query()->with(['kelas', 'waliKelas', 'tugasPertama'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Tingkat Kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.jurusan')
                    ->label('Jurusan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tugas.nama_tugas')
                    ->label('Mapel')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->tugas->map(function ($tugas) {

                            return $tugas->nama_tugas;
                        })->implode('<br>');
                    })
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tugas.status')
                    ->label('Status')

                    ->formatStateUsing(function ($state, $record) {
                        return $record->tugas->map(function ($tugas) {
                            return $tugas->status;
                        })->implode('<br> ');
                    })
                    ->html()

                    ->sortable(),

                Tables\Columns\TextColumn::make('waliKelas.nama_wali')
                    ->label('Wali Kelas')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'view' => Pages\ViewRekapKelas::route('/{record}'),
            'edit' => Pages\EditRekapKelas::route('/{record}/edit'),

        ];
    }
}
