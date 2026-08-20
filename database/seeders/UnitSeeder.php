<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = array(
            array('name' => 'pcs', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'dzn', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'kg', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'meter', 'status' => 1, 'created_at' => now(), 'updated_at' => now())
        );

        Unit::insert($units);
    }
}
