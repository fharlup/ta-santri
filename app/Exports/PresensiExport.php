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

    // Menerima data rekap dari Controller
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Mengambil koleksi data
    public function collection()
    {
        return collect($this->data);
    }

    // Menentukan Header kolom Excel 
    public function headings(): array
    {
        return [
            'Nama Santriwati',
            'Angkatan',
            'Tahajjud',
            'Shubuh',
            'Piket',
            'Apel',
            'HL Dhuha/Kuliah',
            'Sholat Dzuhur',
            'HL Dzuhur/Kuliah',
            'Ashar',
            'BA/BM',
            'Maghrib',
            'Isya',
            'GH/M',
            'Komdis'
        ];
    }

    // Memetakan data ke setiap kolom
    public function map($row): array
    {
        return [
            $row['nama'],
            $row['angkatan'],
            $row['TAHAJJUD'] . '%',
            $row['SHUBUH'] . '%',
            $row['PIKET'] . '%',
            $row['APEL'] . '%',
            $row['HL DHUHA/KULIAH'] . '%',
            $row['SHOLAT DZUHUR'] . '%',
	    ($row['HL DZUHUR/KULIAH'] ?? 0) . '%',
            $row['ASHAR'] . '%',
            $row['BA/BM'] . '%',
            $row['MAGHRIB'] . '%',
            $row['ISYA'] . '%',
            $row['GH/M'] . '%',
            $row['KOMDIS'] . '%'
        ];
    }

    // Memberikan gaya/styling pada header agar rapi
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '473829'] // Warna Cokelat Tunas Qur'an
                ]
            ],
        ];
    }
}