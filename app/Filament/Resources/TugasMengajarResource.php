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

class TugasMengajarResource extends Resource
{
    protected static ?string $model = TugasMengajar::class;
    protected static ?string $navigationLabel = 'Tugas Mengajar'; //label tanpa s
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('kode_guru')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_guru')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kelas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mapel')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jurusan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('kode_guru')
                    ->color('text1')
                    ->icon('heroicon-o-check-circle')
                    ->fontFamily(FontFamily::Mono)
                    ->copyable()
                    ->copyMessage('Kode Guru copied')
                    ->copyMessageDuration(1500)

                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_guru')
                    ->color('text2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->color('text3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mapel')
                    ->color('text4')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan')
                    ->color('text5')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTugasMengajars::route('/'),
            'create' => Pages\CreateTugasMengajar::route('/create'),
            'edit' => Pages\EditTugasMengajar::route('/{record}/edit'),
        ];
    }
}
