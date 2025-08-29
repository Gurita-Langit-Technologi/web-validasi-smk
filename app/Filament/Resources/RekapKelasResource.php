<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapKelasResource\Pages;
use App\Models\RekapKelas;
use App\Models\RekapPengumpulan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RekapKelasResource extends Resource
{
    protected static ?string $model = RekapKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_guru')
                    ->label('Nama Guru Pengampu')
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
                RekapKelas::query()->with(['kelas', 'waliKelas', 'guru', 'rekapPengumpulan', 'mapel'])
            )

            // ✅ tambah aggregate per baris pakai withCount
            ->modifyQueryUsing(function (Builder $query) {
                $query->withCount([
                    'rekapPengumpulan as selesai_count' => fn($q) => $q->where('status', 'selesai'),
                    'rekapPengumpulan as belum_count'   => fn($q) => $q->where('status', 'belum selesai'),
                ]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Tingkat Kelas')
                    ->color('text1')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.kompetensi_keahlian')
                    ->label('Kompt Keahlian')
                    ->color('text1')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mapel.nama_diklat')
                    ->label('Mapel')
                    ->color('text1')
                    // ->formatStateUsing(
                    //     fn($state, $record) =>
                    //     $record->tugas->pluck('nama_diklat')->implode('<br>')
                    // )
                    // ->html()
                    ->sortable(),



                // Jumlah selesai per mapel (per baris RekapKelas)
                Tables\Columns\TextColumn::make('selesai_count')
                    ->label('Selesai')
                    ->badge()
                    ->color('text2')
                    ->sortable(),

                // Jumlah belum per mapel
                Tables\Columns\TextColumn::make('belum_count')
                    ->label('Belum')
                    ->badge()
                    ->color('danger')
                    ->sortable(),



                Tables\Columns\TextColumn::make('guru.nama_guru')
                    ->label('Nama Guru')
                    ->color('text1')
                    ->sortable(),

                Tables\Columns\TextColumn::make('waliKelas.nama_wali')
                    ->label('Wali Kelas')
                    ->color('text1')
                    ->sortable(),
            ])


            ->filters([])

            // 🔹 Tambahkan Summary Card di Header
            ->header(function () {
                // gunakan model & penamaan yang benar
                $total   = \App\Models\RekapPengumpulan::count();
                $selesai = \App\Models\RekapPengumpulan::where('status', 'selesai')->count();
                $belum   = $total - $selesai;
                $persen  = $total > 0 ? round(($selesai / $total) * 100, 2) : 0;

                return view('filament.tables.headers.rekap-summary', compact('total', 'selesai', 'belum', 'persen'));
            })

            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapKelas::route('/'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
            \App\Filament\Resources\RekapKelasResource\Widgets\RekapKelasStatsOverview::class,
        ];
    }
}
