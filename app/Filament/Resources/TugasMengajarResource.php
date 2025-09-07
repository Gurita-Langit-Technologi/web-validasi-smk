<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TugasMengajarResource\Pages;
use App\Filament\Resources\TugasMengajarResource\RelationManagers;
use App\Models\TugasMengajar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontFamily;
use App\Filament\Imports\TugasMengajarImporter;
use Filament\Tables\Actions\ImportAction;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;

class TugasMengajarResource extends Resource
{
    protected static ?string $model = TugasMengajar::class;

    protected static ?string $navigationBadgeTooltip = 'Jumlah data';
    protected static ?string $navigationLabel = 'Tugas Mengajar'; //label tanpa s
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('id_guru')
                    ->label('Kode Guru')
                    ->relationship('guru', 'kode_guru') // pastikan field 'kode_kelas' ada di tabel kelas
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('id_kelas')
                    ->label('Kelas')
                    // ->multiple()
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('id_mapel')
                    ->label('Mapel')
                    ->relationship('mapel', 'nama_diklat')
                    ->searchable()
                    ->preload()
                    ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->query(
                TugasMengajar::query()->with(['kelas', 'guru', 'mapel'])
            )
            ->columns([


                Tables\Columns\TextColumn::make('guru.kode_guru')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->sortable()
                    ->label('Kode Guru')
                    ->color('text1')
                    ->icon('heroicon-o-check-circle')
                    ->fontFamily(FontFamily::Mono)
                    ->copyable()
                    ->copyMessage('Kode Guru copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->color('text2')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->color('text3')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mapel.nama_diklat')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->sortable()
                    ->color('text3')
                    ->label('Mapel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.kompetensi_keahlian')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->sortable()

                    ->color('text5')
                    ->label('Kompetensi Keahlian')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //  Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //  ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(TugasMengajarImporter::class)
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
        return 'Tugas Mengajar';
    }
    /*  public static function getNavigationBadge(): ?string
     {
         return static::getModel()::count() > 0 ? 'warning' : 'primary';
     }
         */
    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::$model::count();

        return 'text4';
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTugasMengajars::route('/'),
            'create' => Pages\CreateTugasMengajar::route('/create'),
            'edit' => Pages\EditTugasMengajar::route('/{record}/edit'),
        ];
    }
}
