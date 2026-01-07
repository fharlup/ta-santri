<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenilaianExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $listUstadzah;

    public function __construct($data, $listUstadzah)
    {
        $this->data = $data;
        $this->listUstadzah = $listUstadzah;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        // Header otomatis: Nama, Angkatan, [Nama-Nama Ustadzah], Total Akhir
        return array_merge(['Nama Santriwati', 'Angkatan'], $this->listUstadzah, ['Total Akumulasi']);
    }

    public function map($row): array
    {
        $mapped = [$row['nama'], $row['angkatan']];
        $totalPoinSantri = 0;

        foreach ($this->listUstadzah as $nama) {
            $nilai = $row[$nama] ?? 0;
            $mapped[] = $nilai;
            $totalPoinSantri += $nilai;
        }

        $mapped[] = $totalPoinSantri;
        return $mapped;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B763B']]
            ],
        ];
    }
}