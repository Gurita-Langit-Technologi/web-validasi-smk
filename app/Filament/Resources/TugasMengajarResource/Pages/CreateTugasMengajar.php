<?php

namespace App\Filament\Resources\TugasMengajarResource\Pages;

use App\Filament\Resources\TugasMengajarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Guru;

class CreateTugasMengajar extends CreateRecord
{
    protected static string $resource = TugasMengajarResource::class;
    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $guru = \App\Models\Guru::find($data['id_guru']);
        $kelas = \App\Models\Kelas::find($data['id_kelas']);
        $mapel = \App\Models\Mapel::find($data['id_mapel']);

        $data['kode_guru'] = $guru?->kode_guru;
        $data['kelas'] = $kelas?->nama_kelas; // atau nama_kelas, sesuaikan dengan kolom yang ada
        $data['mata_diklat'] = $mapel?->nama_diklat;

        // kompetensi_keahlian kamu sudah isi dari form Filament
        // jadi tidak perlu diambil dari relasi kalau sudah dipilih user

        return $data;
    }
}
