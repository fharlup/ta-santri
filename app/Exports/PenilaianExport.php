<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenilaianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function collection() {
        return $this->data;
    }

    public function headings(): array {
        return [
            'Nama Santriwati', 'Angkatan', 'Tanggal', 'Disiplin', 'K3', 'T.Jawab', 
            'Kreatifitas', 'Adab', 'Berterate', 'Kesabaran', 'Produktif', 
            'Mandiri', 'Optimis', 'Kejujuran', 'Deskripsi'
        ];
    }

    public function map($p): array {
        return [
            $p->santriwati->nama_lengkap,
            $p->angkatan,
            $p->tanggal->format('d/m/Y'),
            $p->disiplin, $p->k3, $p->tanggung_jawab, $p->inisiatif_kreatifitas,
            $p->adab, $p->berterate, $p->integritas_kesabaran, $p->integritas_produktif,
            $p->integritas_mandiri, $p->integritas_optimis, $p->integritas_kejujuran,
            $p->deskripsi
        ];
    }
}