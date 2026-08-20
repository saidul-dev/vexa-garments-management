<?php

namespace Database\Seeders;

use App\Models\AccessoryOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessoryOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accessory_orders = array(
            array('invoice_no' => 'AINV-00001','user_id' => '2','accessory_id' => '3','party_id' => '4','qty_unit' => '444','unit_price' => '6','ttl_amount' => '2664','created_at' => now(),'updated_at' => now()),
            array('invoice_no' => 'AINV-00002','user_id' => '2','accessory_id' => '6','party_id' => '4','qty_unit' => '52','unit_price' => '44','ttl_amount' => '2288','created_at' => now(),'updated_at' => now()),
            array('invoice_no' => 'AINV-00003','user_id' => '1','accessory_id' => '6','party_id' => '4','qty_unit' => '4','unit_price' => '44','ttl_amount' => '176','created_at' => now(),'updated_at' => now()),
            array('invoice_no' => 'AINV-00004','user_id' => '1','accessory_id' => '4','party_id' => '4','qty_unit' => '35','unit_price' => '9','ttl_amount' => '315','created_at' => now(),'updated_at' => now()),
            array('invoice_no' => 'AINV-00005','user_id' => '2','accessory_id' => '4','party_id' => '4','qty_unit' => '5555','unit_price' => '9','ttl_amount' => '49995','created_at' => now(),'updated_at' => now())
        );

        AccessoryOrder::insert($accessory_orders);
    }
}
