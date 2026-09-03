<?php

namespace App\Exports;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\RekapPengumpulan;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RekapTugasExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithColumnWidths,
    WithDrawings,
    WithEvents,
    WithTitle
{
    protected $id_mapel;
    protected $id_kelas;
    protected $tahunAjaran;

    protected $mapel;
    protected $kelas;
    protected $namaMapel = '-';
    protected $namaGuru = '-';
    protected $kodeGuru = '-';
    protected $namaKelas = 'Semua Kelas';

    protected $data = [];
    protected $mergeInfo = [];
    protected $statusRows = [];
    protected $totalSiswa = 0;
    protected $totalRows = 0;
    protected $lastDataRow = 9;

    public function __construct($id_mapel, $id_kelas = null, $tahunAjaran = null)
    {
        $this->id_mapel = $id_mapel;
        $this->id_kelas = $id_kelas;

        // Tahun Ajaran otomatis atau dari input
        if (!$tahunAjaran) {
            $tahunAjaran = request('tahun_ajaran');
        }
        if (!$tahunAjaran) {
            $currentYear = (int) date('Y');
            $currentMonth = (int) date('n');
            $tahunAjaran = ($currentMonth >= 7)
                ? $currentYear . '/' . ($currentYear + 1)
                : ($currentYear - 1) . '/' . $currentYear;
        }
        $this->tahunAjaran = $tahunAjaran;

        // Load detail Mapel & Guru
        $this->mapel = Mapel::with('guru')->find($this->id_mapel);
        if ($this->mapel) {
            $this->namaMapel = $this->mapel->nama_diklat ?? '-';
            if ($this->mapel->guru) {
                $this->namaGuru = $this->mapel->guru->nama_guru ?? '-';
                $this->kodeGuru = $this->mapel->guru->kode_guru ?? '-';
            }
        }

        // Load detail Kelas
        if ($this->id_kelas) {
            $this->kelas = Kelas::find($this->id_kelas);
            if ($this->kelas) {
                $this->namaKelas = $this->kelas->nama_kelas;
            }
        } else {
            $firstRekap = RekapPengumpulan::with('rekapKelas.kelas')
                ->where('id_mapel', $this->id_mapel)
                ->first();
            if ($firstRekap && $firstRekap->rekapKelas && $firstRekap->rekapKelas->kelas) {
                $this->namaKelas = $firstRekap->rekapKelas->kelas->nama_kelas;
            }
        }
    }

    public function title(): string
    {
        return 'Rekap Tugas';
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,   // NO
            'B' => 30,  // NAMA SISWA
            'C' => 32,  // NAMA TUGAS
            'D' => 18,  // STATUS
            'E' => 24,  // TANGGAL PENGUMPULAN
            'F' => 12,  // NILAI
            'G' => 28,  // KETERANGAN
        ];
    }

    public function drawings()
    {
        $logoPath = public_path('images/fic.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo SMK');
            $drawing->setDescription('Logo SMK PGRI 1 Banyuwangi');
            $drawing->setPath($logoPath);
            $drawing->setHeight(68);
            $drawing->setCoordinates('A2');
            $drawing->setOffsetX(12);
            $drawing->setOffsetY(4);
            return $drawing;
        }
        return [];
    }

    public function collection()
    {
        $query = RekapPengumpulan::with(['siswa', 'rekapKelas.kelas'])
            ->where('id_mapel', $this->id_mapel);

        if ($this->id_kelas) {
            $query->whereHas('rekapKelas', function ($q) {
                $q->where('id_kelas', $this->id_kelas);
            });
        }

        $rekapData = $query->orderBy('id_siswa')->get();

        $groupedData = [];
        foreach ($rekapData as $rekap) {
            $groupedData[$rekap->id_siswa][] = $rekap;
        }

        $no = 1;
        $currentRow = 10; // Data dimulai di baris 10 (headings di baris 9)
        $this->mergeInfo = [];
        $this->statusRows = [];
        $this->data = [];

        foreach ($groupedData as $siswaId => $tugasList) {
            $siswa = Siswa::find($siswaId);
            $namaSiswa = $siswa->nama_siswa ?? 'Tidak Diketahui';
            $taskCount = count($tugasList);

            $startRow = $currentRow;
            $endRow = $currentRow + $taskCount - 1;

            if ($taskCount > 1) {
                $this->mergeInfo[] = [
                    'start' => $startRow,
                    'end' => $endRow,
                ];
            }

            foreach ($tugasList as $index => $tugasData) {
                $tgl = $tugasData->tanggal_pengumpulan;
                $formattedDate = '-';
                if (!empty($tgl)) {
                    $timestamp = strtotime($tgl);
                    $formattedDate = $timestamp ? date('d-m-Y', $timestamp) : $tgl;
                }

                $status = !empty($tugasData->status) ? $tugasData->status : 'Belum Selesai';
                $nilai = ($tugasData->nilai !== null && $tugasData->nilai !== '') ? $tugasData->nilai : '-';
                $keterangan = !empty($tugasData->keterangan) ? $tugasData->keterangan : '-';

                $this->data[] = [
                    'no' => $index === 0 ? $no : '',
                    'nama_siswa' => $index === 0 ? $namaSiswa : '',
                    'nama_tugas' => $tugasData->nama_tugas,
                    'status' => $status,
                    'tanggal_pengumpulan' => $formattedDate,
                    'nilai' => $nilai,
                    'keterangan' => $keterangan,
                ];

                $this->statusRows[$currentRow] = $status;
                $currentRow++;
            }

            $no++;
        }

        $this->totalSiswa = count($groupedData);
        $this->totalRows = count($this->data);
        $this->lastDataRow = $currentRow > 10 ? ($currentRow - 1) : 10;

        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA SISWA',
            'NAMA TUGAS',
            'STATUS',
            'TANGGAL PENGUMPULAN',
            'NILAI',
            'KETERANGAN'
        ];
    }

    public function map($row): array
    {
        return [
            $row['no'],
            $row['nama_siswa'],
            $row['nama_tugas'],
            $row['status'],
            $row['tanggal_pengumpulan'],
            $row['nilai'],
            $row['keterangan']
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Page Setup
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // 2. Set Row Heights Header
                $sheet->getRowDimension(1)->setRowHeight(10);
                $sheet->getRowDimension(2)->setRowHeight(24);
                $sheet->getRowDimension(3)->setRowHeight(20);
                $sheet->getRowDimension(4)->setRowHeight(18);
                $sheet->getRowDimension(5)->setRowHeight(6);
                $sheet->getRowDimension(6)->setRowHeight(18);
                $sheet->getRowDimension(7)->setRowHeight(18);
                $sheet->getRowDimension(8)->setRowHeight(10);
                $sheet->getRowDimension(9)->setRowHeight(28);

                // 3. Kop Laporan / Judul (Merge B-G agar logo di A2 tidak tertimpa)
                $sheet->mergeCells('B2:G2');
                $sheet->mergeCells('B3:G3');
                $sheet->mergeCells('B4:G4');

                $sheet->setCellValue('B2', 'SMK PGRI 1 BANYUWANGI');
                $sheet->setCellValue('B3', 'REKAPITULASI PENGUMPULAN TUGAS SISWA');
                $sheet->setCellValue('B4', 'TAHUN AJARAN ' . $this->tahunAjaran);

                $sheet->getStyle('B2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '262626'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '595959'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Garis Ganda Pemisah Kop (Row 5)
                $sheet->getStyle('A5:G5')->getBorders()->getBottom()->applyFromArray([
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['rgb' => '1F4E78'],
                ]);

                // 4. Informasi Mata Pelajaran & Kelas (Row 6 - 7)
                $sheet->setCellValue('A6', 'Mata Pelajaran');
                $sheet->setCellValue('B6', ': ' . $this->namaMapel);
                $sheet->setCellValue('E6', 'Guru Pengampu');
                $sheet->setCellValue('F6', ': ' . $this->namaGuru);

                $sheet->setCellValue('A7', 'Kelas');
                $sheet->setCellValue('B7', ': ' . $this->namaKelas);
                $sheet->setCellValue('E7', 'Tanggal Cetak');
                $sheet->setCellValue('F7', ': ' . date('d F Y'));

                $sheet->getStyle('A6:A7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '333333']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('E6:E7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '333333']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('B6:B7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '000000']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('F6:F7')->applyFromArray([
                    'font' => ['size' => 10, 'color' => ['rgb' => '000000']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // 5. Table Header Styling (Row 9)
                $sheet->getStyle('A9:G9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 10,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                // 6. Data Rows Formatting
                if ($this->totalRows > 0) {
                    $startRow = 10;
                    $endRow = $this->lastDataRow;

                    // Row Heights
                    for ($r = $startRow; $r <= $endRow; $r++) {
                        $sheet->getRowDimension($r)->setRowHeight(22);
                    }

                    // Base Alignment & Borders for all data
                    $sheet->getStyle("A{$startRow}:G{$endRow}")->applyFromArray([
                        'font' => ['size' => 10],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'BFBFBF'],
                            ],
                        ],
                    ]);

                    // Column specific alignments
                    $sheet->getStyle("A{$startRow}:A{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$startRow}:C{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("D{$startRow}:F{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("G{$startRow}:G{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    // Merge No & Nama Siswa for students with multiple tasks
                    foreach ($this->mergeInfo as $merge) {
                        $sheet->mergeCells("A{$merge['start']}:A{$merge['end']}");
                        $sheet->mergeCells("B{$merge['start']}:B{$merge['end']}");
                    }

                    // Status Badge Styling (Soft Color Highlights)
                    foreach ($this->statusRows as $rowNum => $statusText) {
                        if (strtolower($statusText) === 'selesai') {
                            $sheet->getStyle("D{$rowNum}")->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'E2EFDA'],
                                ],
                                'font' => [
                                    'bold' => true,
                                    'color' => ['rgb' => '375623'],
                                ],
                            ]);
                        } else {
                            $sheet->getStyle("D{$rowNum}")->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'FCE4D6'],
                                ],
                                'font' => [
                                    'bold' => true,
                                    'color' => ['rgb' => 'C65911'],
                                ],
                            ]);
                        }
                    }

                    // Outline border for entire table
                    $sheet->getStyle("A9:G{$endRow}")->getBorders()->getOutline()->applyFromArray([
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '1F4E78'],
                    ]);

                    // 7. Footer / Summary & Tanda Tangan
                    $summaryRow = $endRow + 2;
                    $sheet->setCellValue("A{$summaryRow}", "Keterangan: Total Siswa = {$this->totalSiswa} Siswa | Total Tugas = {$this->totalRows} Entri");
                    $sheet->getStyle("A{$summaryRow}")->applyFromArray([
                        'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '595959']],
                    ]);

                    // Signature block (Column E)
                    $signDateRow = $summaryRow + 1;
                    $sheet->setCellValue("E{$signDateRow}", "Banyuwangi, " . date('d F Y'));
                    $sheet->setCellValue("E" . ($signDateRow + 1), "Guru Pengampu Mata Pelajaran,");

                    $signNameRow = $signDateRow + 5;
                    $sheet->setCellValue("E{$signNameRow}", $this->namaGuru);
                    $sheet->getStyle("E{$signNameRow}")->applyFromArray([
                        'font' => ['bold' => true, 'underline' => true, 'size' => 10],
                    ]);

                    if (!empty($this->kodeGuru) && $this->kodeGuru !== '-') {
                        $sheet->setCellValue("E" . ($signNameRow + 1), "NIP / Kode Guru: " . $this->kodeGuru);
                        $sheet->getStyle("E" . ($signNameRow + 1))->applyFromArray([
                            'font' => ['size' => 9, 'color' => ['rgb' => '595959']],
                        ]);
                    }
                } else {
                    // Jika data kosong
                    $sheet->setCellValue('A10', 'Tidak ada data rekap pengumpulan tugas untuk ditampilkan.');
                    $sheet->mergeCells('A10:G10');
                    $sheet->getRowDimension(10)->setRowHeight(30);
                    $sheet->getStyle('A10:G10')->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'font' => ['italic' => true, 'color' => ['rgb' => '808080']],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'BFBFBF'],
                            ],
                        ],
                    ]);
                }
            },
        ];
    }
}
