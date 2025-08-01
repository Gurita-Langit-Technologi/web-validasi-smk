<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Models\Kelas;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use App\Models\Siswa;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //  Actions\CreateAction::make(),
        ];
    }
    //customize redirect after create

    public function getTabs(): array
    {

        $tabs = ['all' => Tab::make('Semua')->badge($this->getModel()::count())];

        $kelasList = Kelas::withCount('siswa')
            ->orderBy('tingkat_kelas')
            ->orderBy('nama_kelas')
            ->get();

        foreach ($kelasList as $kelas) {
            $namaKelas = "{$kelas->nama_kelas}";
            $slug = str($namaKelas)->slug()->toString();

            $tabs[$slug] = Tab::make($namaKelas)
                ->badge($kelas->siswa_count)
                ->modifyQueryUsing(function ($query) use ($kelas) {
                    return $query->where('id_kelas', $kelas->id_kelas);
                });
        }

        return $tabs;
    }
}
