<?php

namespace App\Exports;

use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapTugasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $id_mapel;
    protected $data = [];

    public function __construct($id_mapel)
    {
        $this->id_mapel = $id_mapel;
    }

    public function collection()
    {
        $rekapData = RekapPengumpulan::where('id_mapel', $this->id_mapel)
            ->orderBy('id_siswa')
            ->get();

        $groupedData = [];

        foreach ($rekapData as $rekap) {
            $groupedData[$rekap->id_siswa][] = $rekap;
        }

        foreach ($groupedData as $siswaId => $tugas) {
            $siswa = Siswa::find($siswaId);
            $namaSiswa = $siswa->nama_siswa ?? 'Tidak Diketahui';

            foreach ($tugas as $index => $tugasData) {
                $this->data[] = [
                    'nama_siswa' => $index === 0 ? $namaSiswa : '',
                    'nama_tugas' => $tugasData->nama_tugas,
                    'status' => $tugasData->status,
                    'tanggal_pengumpulan' => $tugasData->tanggal_pengumpulan,
                    'keterangan' => $tugasData->keterangan ?? '',
                    'nilai' => $tugasData->nilai ?? '-'
                ];
            }
        }

        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Nama Tugas',
            'Status',
            'Tanggal Pengumpulan',
            'Keterangan',
            'Nilai'
        ];
    }

    public function map($row): array
    {
        return [
            $row['nama_siswa'],
            $row['nama_tugas'],
            $row['status'],
            $row['tanggal_pengumpulan'],
            $row['keterangan'],
            $row['nilai']
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowStart = 2;

                $lastNamaSiswa = null;
                $mergeStartRow = null;

                foreach ($this->data as $index => $row) {
                    $currentRow = $rowStart + $index;

                    if (!empty($row['nama_siswa'])) {
                        if ($mergeStartRow !== null) {
                            $sheet->mergeCells("A{$mergeStartRow}:A" . ($currentRow - 1));
                        }

                        $mergeStartRow = $currentRow;
                        $lastNamaSiswa = $row['nama_siswa'];
                    }
                }

                if ($mergeStartRow !== null) {
                    $sheet->mergeCells("A{$mergeStartRow}:A" . ($currentRow));
                }
            }
        ];
    }
}
