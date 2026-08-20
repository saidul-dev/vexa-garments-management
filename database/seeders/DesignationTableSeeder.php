<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $designations = array(
            array('name' => 'Manager','description' => 'manager','status' => '0','created_at' => '2024-07-12 15:53:54','updated_at' => '2024-07-12 15:53:54'),
            array('name' => 'sublimacion','description' => 'sublimacion','status' => '0','created_at' => '2024-07-22 03:39:16','updated_at' => '2024-07-22 03:39:16')
          );

          Designation::insert($designations);
    }
}
