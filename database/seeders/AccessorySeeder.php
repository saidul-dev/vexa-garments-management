<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accessories = array(
            array('user_id' => '1','unit_id' => '1','name' => 'C9700','unit_price' => '1','description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','unit_id' => '2','name' => 'kundan','unit_price' => '12','description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','unit_id' => '2','name' => 'Silk Chiffon','unit_price' => '6','description' => '80% Silk 20 % Chiffon','status' => '1','created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','unit_id' => '3','name' => 'kk','unit_price' => '9','description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','unit_id' => '3','name' => 'rr','unit_price' => '12','description' => 'test','status' => '1','created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','unit_id' => '2','name' => 'hasan','unit_price' => '1000','description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','unit_id' => '3','name' => 'Jeck','unit_price' => '20','description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now())
          );

        Accessory::insert($accessories);
    }
}
