<?php

namespace App\Exports;

use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapTugasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $id_mapel;

    public function __construct($id_mapel)
    {
        $this->id_mapel = $id_mapel;
    }

    public function collection()
    {
        return RekapPengumpulan::where('id_mapel', $this->id_mapel)->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Nama Tugas',
            'Status',
            'Tanggal Pengumpulan',
            'Keterangan'
        ];
    }

    public function map($rekap): array
    {
        $siswa = Siswa::find($rekap->id_siswa);
        $tugas = RekapPengumpulan::find($rekap->id_tugas);

        return [
            $siswa->nama_siswa ?? 'Tidak Diketahui',
            $tugas->nama_tugas ?? 'Tidak Diketahui',
            $rekap->status,
            $rekap->tanggal_pengumpulan,
            $rekap->keterangan ?? ' '
        ];
    }
}
