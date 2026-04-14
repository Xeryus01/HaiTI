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
                'holder' => 'Ahmad Surya',
                'status' => 'ACTIVE',
                'condition' => 'GOOD',
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
                'holder' => 'Divisi IT',
                'status' => 'ACTIVE',
                'condition' => 'GOOD',
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
                'holder' => 'Tim Infrastructure',
                'status' => 'ACTIVE',
                'condition' => 'GOOD',
                'purchased_at' => now()->subYears(2),
            ],
            [
                'asset_code' => 'AST-004',
                'name' => 'Lenovo ThinkPad T14s',
                'type' => 'Laptop',
                'brand' => 'Lenovo',
                'model' => 'ThinkPad T14s',
                'serial_number' => 'TP-T14S-022',
                'specs' => ['processor' => 'AMD Ryzen 7', 'ram' => '32GB', 'storage' => '1TB SSD'],
                'location' => 'Gedung B - Lantai 1',
                'holder' => 'Siti Nurhaliza',
                'status' => 'ACTIVE',
                'condition' => 'GOOD',
                'purchased_at' => now()->subMonths(3),
            ],
            [
                'asset_code' => 'AST-005',
                'name' => 'Cisco Catalyst 2960',
                'type' => 'Switch',
                'brand' => 'Cisco',
                'model' => 'Catalyst 2960',
                'serial_number' => 'CC-2960-045',
                'specs' => ['ports' => '24', 'speed' => '1Gbps', 'poe' => 'Yes'],
                'location' => 'Network Room',
                'holder' => 'Tim Network',
                'status' => 'ACTIVE',
                'condition' => 'FAIR',
                'purchased_at' => now()->subYears(1)->subMonths(6),
            ],
            [
                'asset_code' => 'AST-006',
                'name' => 'Samsung 32" Monitor',
                'type' => 'Monitor',
                'brand' => 'Samsung',
                'model' => 'LU32R590C',
                'serial_number' => 'SM-32LU-089',
                'specs' => ['size' => '32 inch', 'resolution' => '4K UHD', 'panel' => 'VA'],
                'location' => 'Meeting Room A',
                'holder' => 'Meeting Room A',
                'status' => 'ACTIVE',
                'condition' => 'GOOD',
                'purchased_at' => now()->subMonths(12),
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
