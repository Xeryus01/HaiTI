<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $assetCode = trim((string) ($row['asset_code'] ?? $row['kode_aset'] ?? ''));
            if ($assetCode === '') {
                continue;
            }

            $status = strtoupper(trim((string) ($row['status'] ?? $row['Status'] ?? 'ACTIVE')));
            $condition = strtoupper(trim((string) ($row['condition'] ?? $row['kondisi'] ?? 'GOOD')));

            $data = [
                'name' => trim((string) ($row['name'] ?? $row['nama'] ?? '')),
                'type' => trim((string) ($row['type'] ?? $row['tipe'] ?? '')),
                'brand' => trim((string) ($row['brand'] ?? $row['merek'] ?? '')),
                'model' => trim((string) ($row['model'] ?? '')),
                'serial_number' => trim((string) ($row['serial_number'] ?? $row['nomor_seri'] ?? '')),
                'specs' => $this->normalizeSpecs($row['specs'] ?? $row['spesifikasi'] ?? ''),
                'location' => trim((string) ($row['location'] ?? $row['lokasi'] ?? '')),
                'holder' => trim((string) ($row['holder'] ?? $row['pemegang'] ?? '')),
                'status' => in_array($status, ['ACTIVE', 'MAINTENANCE', 'BROKEN', 'RETIRED', 'SOLD', 'INACTIVE'], true) ? $status : 'ACTIVE',
                'condition' => in_array($condition, ['GOOD', 'FAIR', 'POOR'], true) ? $condition : 'GOOD',
                'purchased_at' => $this->normalizeDate($row['purchased_at'] ?? $row['tanggal_dibeli'] ?? null),
            ];

            Asset::updateOrCreate(
                ['asset_code' => $assetCode],
                array_merge(['asset_code' => $assetCode], $data)
            );
        }
    }

    protected function normalizeSpecs($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, '{') && Str::endsWith($value, '}')) {
            return $value;
        }

        return json_encode(array_filter(array_map('trim', explode(',', $value))));
    }

    protected function normalizeDate($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
