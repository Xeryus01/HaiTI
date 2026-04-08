<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            [
                'asset_code' => 'AST-001',
                'name' => 'Dell Latitude 7420',
                'type' => 'Laptop',
                'brand' => 'Dell',
                'model' => 'Latitude 7420',
                'serial_number' => 'DL7420-001',
                'specs' => ['processor' => 'Intel Core i7', 'ram' => '16GB', 'storage' => '512GB SSD'],
                'location' => 'Gedung A - Lantai 1',
                'status' => 'ACTIVE',
                'purchased_at' => now()->subMonths(8),
            ],
            [
                'asset_code' => 'AST-002',
                'name' => 'HP LaserJet Pro M404n',
                'type' => 'Printer',
                'brand' => 'HP',
                'model' => 'LaserJet Pro M404n',
                'serial_number' => 'HP404N-015',
                'specs' => ['print_speed' => '40 ppm', 'duplex' => 'Automatic', 'connectivity' => 'USB, Ethernet'],
                'location' => 'Gedung A - Lantai 2',
                'status' => 'ACTIVE',
                'purchased_at' => now()->subYear(),
            ],
            [
                'asset_code' => 'AST-003',
                'name' => 'Dell PowerEdge R640',
                'type' => 'Server',
                'brand' => 'Dell',
                'model' => 'PowerEdge R640',
                'serial_number' => 'PE-R640-007',
                'specs' => ['processor' => 'Dual Intel Xeon', 'ram' => '128GB', 'storage' => '2TB SSD'],
                'location' => 'Data Center',
                'status' => 'ACTIVE',
                'purchased_at' => now()->subYears(2),
            ],
        ];

        foreach ($assets as $asset) {
            Asset::updateOrCreate(
                ['asset_code' => $asset['asset_code']],
                $asset
            );
        }
    }
}
