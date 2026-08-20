<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenses = array(
            array('category_name' => 'Supplier Payment','party_id' => NULL,'total_bill' => '0','total_paid' => '0','total_due' => '0','expense_description' => 'Supplier Payment','status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'AINV-00001','party_id' => '4','total_bill' => '2664','total_paid' => '0','total_due' => '2664','expense_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'Transport','party_id' => NULL,'total_bill' => '0','total_paid' => '0','total_due' => '0','expense_description' => 'Transport','status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'Daily Expense','party_id' => NULL,'total_bill' => '0','total_paid' => '0','total_due' => '0','expense_description' => 'Daily Expense','status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'AINV-00002','party_id' => '4','total_bill' => '2288','total_paid' => '0','total_due' => '2288','expense_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'AINV-00003','party_id' => '4','total_bill' => '176','total_paid' => '0','total_due' => '176','expense_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'AINV-00004','party_id' => '4','total_bill' => '315','total_paid' => '0','total_due' => '315','expense_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('category_name' => 'AINV-00005','party_id' => '4','total_bill' => '49995','total_paid' => '0','total_due' => '49995','expense_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now())
          );

        Expense::insert($expenses);
    }
}
