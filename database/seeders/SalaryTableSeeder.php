<?php

namespace Database\Seeders;

use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salaries = array(
            array('voucher_id' => '1','employee_id' => '1','bank_id' => NULL,'user_id' => '1','year' => '2024','month' => 'July','payment_method' => 'cash','amount' => '2000','due_salary' => '198000','notes' => NULL,'meta' => '{"cheque_no":null,"issue_date":"2024-07-23"}','deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('voucher_id' => '2','employee_id' => '1','bank_id' => '1','user_id' => '1','year' => '2024','month' => 'July','payment_method' => 'cash','amount' => '20000','due_salary' => '178000','notes' => NULL,'meta' => '{"cheque_no":null,"issue_date":"2024-07-29"}','deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('voucher_id' => '3','employee_id' => '1','bank_id' => NULL,'user_id' => '2','year' => '2024','month' => 'August','payment_method' => 'cash','amount' => '200000','due_salary' => '0','notes' => 'xyz','meta' => '{"cheque_no":null,"issue_date":"2024-08-06"}','deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('voucher_id' => '4','employee_id' => '1','bank_id' => '1','user_id' => '1','year' => '2024','month' => 'September','payment_method' => 'bank','amount' => '20000','due_salary' => '180000','notes' => NULL,'meta' => '{"cheque_no":null,"issue_date":"2024-08-24"}','deleted_at' => NULL,'created_at' => now(),'updated_at' => now())
        );

        Salary::insert($salaries);
    }
}
