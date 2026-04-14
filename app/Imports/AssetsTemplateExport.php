<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsTemplateExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return new Collection([
            [
                'asset_code' => 'ASSET-0001',
                'name' => 'Laptop Dell Vostro 3510',
                'type' => 'Laptop',
                'brand' => 'Dell',
                'model' => 'Vostro 3510',
                'serial_number' => 'SN123456',
                'specs' => '{"prosesor":"i5","ram":"16GB","penyimpanan":"512GB SSD"}',
                'location' => 'Ruang Server',
                'holder' => 'TI',
                'status' => 'ACTIVE',
                'condition' => 'GOOD',
                'purchased_at' => now()->format('Y-m-d'),
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'kode_aset',
            'nama',
            'tipe',
            'merek',
            'model',
            'nomor_seri',
            'spesifikasi',
            'lokasi',
            'pemegang',
            'status',
            'kondisi',
            'tanggal_dibeli',
        ];
    }
}
