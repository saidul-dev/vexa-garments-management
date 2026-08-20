<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = array(
            array('designation_id' => '1','name' => 'Test','email' => 'ga@gmaio.com','address' => 'dfgdf','gender' => 'male','phone' => '1234567876','salary' => '200000.000','employee_type' => NULL,'join_date' => '2024-07-12','birth_date' => '2000-01-01','status' => '1','meta' => '{"nid_front":null,"nid_back":null}','created_at' => '2024-07-12 15:54:23','updated_at' => '2024-07-18 16:28:53')
          );

          Employee::insert($employees);
    }
}
