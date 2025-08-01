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
use App\Filament\Imports\TugasMEngajarImporter;
use Filament\Tables\Actions\ImportAction;

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
                Forms\Components\TextInput::make('kode_guru')
                    ->afterStateUpdated(fn($state, callable $set) => $set('name', strtoupper($state ?? '')))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_guru')
                    ->required()
                    ->afterStateUpdated(fn($state, callable $set) => $set('name', strtoupper($state ?? '')))
                    ->maxLength(255),
                Forms\Components\TextInput::make('kelas')
                    ->afterStateUpdated(fn($state, callable $set) => $set('name', strtoupper($state ?? '')))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_diklat')
                    ->afterStateUpdated(fn($state, callable $set) => $set('name', strtoupper($state ?? '')))
                    ->required()
                    ->label('Mapel')
                    ->maxLength(255),
                Forms\Components\Select::make('kompetensi_keahlian')
                    ->options([
                        'TM' => 'Teknik Pemesinan',
                        'TO' => 'Teknik Otomotif',
                        'TE' => 'Teknik Elektro',
                        'AKL' => 'Akutansi Lembaga dan Keuangan'
                    ])
                    ->preload()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->query(
                TugasMengajar::query()->with(['kelas'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_guru')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))

                    ->color('text1')
                    ->icon('heroicon-o-check-circle')
                    ->fontFamily(FontFamily::Mono)
                    ->copyable()
                    ->copyMessage('Kode Guru copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_guru')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->color('text2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mata_diklat')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
                    ->color('text3')
                    ->label('Mapel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kompetensi_keahlian')
                    ->formatStateUsing(fn($state) => strtoupper($state ?? ''))
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
