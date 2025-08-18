<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerwalianKelasResource\Pages;
use App\Filament\Resources\PerwalianKelasResource\RelationManagers;
use App\Models\PerwalianKelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Filament\Imports\PerwalianKelasImporter;
use Filament\Tables\Actions\ImportAction;

class PerwalianKelasResource extends Resource
{
    protected static ?string $model = PerwalianKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_guru')
                    ->relationship('guru', 'nama_guru')
                    ->required()
                    ->unique(
                        table: PerwalianKelas::class,
                        column: 'id_guru',
                        ignoreRecord: true, // penting untuk update

                    )
                    ->validationMessages([
                        'unique' => 'Guru ini sudah menjadi wali kelas.',
                    ])
                    ->label('Nama Wali'),

                Select::make('id_kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()

                    ->label('Kelas'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                PerwalianKelas::query()->with(['kelas', 'guru'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('guru.kode_guru')
                    ->label('Kode Guru')
                    ->color('text1')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->label('Nama Guru')
                    ->color('text2')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Nama Kelas')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.tingkat_kelas')
                    ->label('Tingkat Kelas')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.kompetensi_keahlian')
                    ->label('Kompetensi Keahlian')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->importer(PerwalianKelasImporter::class)
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

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::$model::count();

        return 'text2';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerwalianKelas::route('/'),
            'create' => Pages\CreatePerwalianKelas::route('/create'),
            'edit' => Pages\EditPerwalianKelas::route('/{record}/edit'),
        ];
    }
}
