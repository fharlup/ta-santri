<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $listKegiatan;

    // Konstruktor sekarang menerima data rekap DAN daftar kegiatan
    public function __construct($data, $listKegiatan)
    {
        $this->data = $data;
        $this->listKegiatan = $listKegiatan;
    }

    public function collection()
    {
        return collect($this->data);
    }

    // Header dinamis: Nama, Angkatan, lalu daftar kegiatan dari DB
    public function headings(): array
    {
        return array_merge(['Nama Santriwati', 'Angkatan'], $this->listKegiatan);
    }

    // Mapping dinamis: Mengambil nilai berdasarkan nama kegiatan yang ada di DB
    public function map($row): array
    {
        $mapped = [
            $row['nama'],
            $row['angkatan'],
        ];

        // Looping untuk mengisi kolom kegiatan secara otomatis
        foreach ($this->listKegiatan as $keg) {
            $mapped[] = ($row[$keg] ?? 0) . '%';
        }

        return $mapped;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '473829']
                ]
            ],
        ];
    }
}