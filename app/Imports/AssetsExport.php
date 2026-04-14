<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AssetsExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate) : null;
        $this->endDate = $endDate ? Carbon::parse($endDate) : null;
    }

    public function collection(): Collection
    {
        $query = Asset::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('purchased_at', [$this->startDate, $this->endDate]);
        }

        return $query->get()->map(function ($asset) {
            return [
                'asset_code' => $asset->asset_code,
                'name' => $asset->name,
                'type' => $asset->type,
                'brand' => $asset->brand,
                'model' => $asset->model,
                'serial_number' => $asset->serial_number,
                'specs' => $asset->specs ? json_encode($asset->specs, JSON_UNESCAPED_UNICODE) : '',
                'location' => $asset->location,
                'holder' => $asset->holder,
                'status' => $asset->status_label,
                'condition' => $asset->condition_label,
                'purchased_at' => $asset->purchased_at ? $asset->purchased_at->format('Y-m-d') : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Nama',
            'Tipe',
            'Merek',
            'Model',
            'Nomor Seri',
            'Spesifikasi',
            'Lokasi',
            'Pemegang',
            'Status',
            'Kondisi',
            'Tanggal Dibeli',
        ];
    }
}