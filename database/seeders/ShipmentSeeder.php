<?php

namespace Database\Seeders;

use App\Models\Shipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shipments = array(
            array('user_id' => '2','order_id' => '2','invoice_no' => 'SHIP-2024-0001','created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '3','invoice_no' => 'SHIP-2024-0002','created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '3','invoice_no' => 'SHIP-2024-0005','created_at' => now(),'updated_at' => now())
        );

        Shipment::insert($shipments);
    }
}
