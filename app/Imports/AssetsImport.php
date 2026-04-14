<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetsImport implements ToCollection, WithHeadingRow
{
    protected array $headerMap = [
        'asset_code' => ['asset_code', 'kode_aset', 'kode aset', 'kode asset'],
        'name' => ['name', 'nama'],
        'type' => ['type', 'tipe'],
        'brand' => ['brand', 'merek'],
        'model' => ['model'],
        'serial_number' => ['serial_number', 'nomor_seri', 'nomor seri'],
        'specs' => ['specs', 'spesifikasi', 'spesifikasi tambahan'],
        'location' => ['location', 'lokasi'],
        'holder' => ['holder', 'pemegang'],
        'status' => ['status'],
        'condition' => ['condition', 'kondisi'],
        'purchased_at' => ['purchased_at', 'tanggal_dibeli', 'tanggal dibeli', 'purchase_date'],
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $row = $this->normalizeRow($row->toArray());

            $assetCode = trim($this->getValue($row, 'asset_code', ''));
            if ($assetCode === '') {
                continue;
            }

            $status = strtoupper($this->getValue($row, 'status', 'ACTIVE'));
            $condition = strtoupper($this->getValue($row, 'condition', 'GOOD'));

            $data = [
                'name' => trim($this->getValue($row, 'name', '')),
                'type' => trim($this->getValue($row, 'type', '')),
                'brand' => trim($this->getValue($row, 'brand', '')),
                'model' => trim($this->getValue($row, 'model', '')),
                'serial_number' => trim($this->getValue($row, 'serial_number', '')),
                'specs' => $this->normalizeSpecs($this->getValue($row, 'specs', '')),
                'location' => trim($this->getValue($row, 'location', '')),
                'holder' => trim($this->getValue($row, 'holder', '')),
                'status' => in_array($status, ['ACTIVE', 'MAINTENANCE', 'BROKEN', 'RETIRED', 'SOLD', 'INACTIVE'], true) ? $status : 'ACTIVE',
                'condition' => in_array($condition, ['GOOD', 'FAIR', 'POOR'], true) ? $condition : 'GOOD',
                'purchased_at' => $this->normalizeDate($this->getValue($row, 'purchased_at', null)),
            ];

            $unknownValues = $this->extractUnknownColumns($row);
            if (!empty($unknownValues)) {
                $data['specs'] = $this->mergeSpecs($data['specs'], $unknownValues);
            }

            Asset::updateOrCreate(
                ['asset_code' => $assetCode],
                array_merge(['asset_code' => $assetCode], $data)
            );
        }
    }

    protected function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $cleanKey = Str::of((string) $key)
                ->trim()
                ->lower()
                ->replaceMatches('/[\s\t\r\n]+/', '_')
                ->__toString();

            $normalized[$cleanKey] = $value;
        }

        return $normalized;
    }

    protected function getValue(array $row, string $field, $default = null): string
    {
        foreach ($this->headerMap[$field] as $key) {
            $normalizedKey = Str::of($key)
                ->trim()
                ->lower()
                ->replaceMatches('/[\s\t\r\n]+/', '_')
                ->__toString();

            if (array_key_exists($normalizedKey, $row) && trim((string) $row[$normalizedKey]) !== '') {
                return trim((string) $row[$normalizedKey]);
            }
        }

        return $default === null ? '' : (string) $default;
    }

    protected function extractUnknownColumns(array $row): array
    {
        $knownKeys = [];
        foreach ($this->headerMap as $keys) {
            foreach ($keys as $key) {
                $knownKeys[] = Str::of($key)
                    ->trim()
                    ->lower()
                    ->replaceMatches('/[\s\t\r\n]+/', '_')
                    ->__toString();
            }
        }

        $knownKeys = array_unique($knownKeys);
        $unknown = [];

        foreach ($row as $key => $value) {
            if ($value === null || trim((string) $value) === '') {
                continue;
            }

            if (in_array($key, $knownKeys, true)) {
                continue;
            }

            $unknown[$key] = trim((string) $value);
        }

        return $unknown;
    }

    protected function normalizeSpecs($value): ?string
    {
        if (is_array($value)) {
            $value = json_encode($value);
        } else {
            $value = trim((string) $value);
        }

        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, '{') && Str::endsWith($value, '}')) {
            return $value;
        }

        return json_encode(array_filter(array_map('trim', explode(',', $value))));
    }

    protected function mergeSpecs(?string $existingSpecs, array $extras): ?string
    {
        $specs = [];

        if ($existingSpecs !== null && $existingSpecs !== '') {
            $decoded = json_decode($existingSpecs, true);
            if (is_array($decoded)) {
                $specs = $decoded;
            } else {
                $specs = array_filter(array_map('trim', explode(',', $existingSpecs)));
            }
        }

        foreach ($extras as $key => $value) {
            $specs[$key] = $value;
        }

        return empty($specs) ? null : json_encode($specs);
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
