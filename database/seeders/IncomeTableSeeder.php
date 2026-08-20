<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $incomes = array(
            array('party_id' => NULL,'category_name' => 'Customer Payment','total_bill' => '0','total_paid' => '0','total_due' => '0','income_description' => 'Customer Payment','status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '3','category_name' => '0000001','total_bill' => '390','total_paid' => '0','total_due' => '390','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '3','category_name' => '0000002','total_bill' => '8100','total_paid' => '0','total_due' => '8100','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '6','category_name' => '0000003','total_bill' => '110000','total_paid' => '0','total_due' => '110000','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '5','category_name' => '0000005','total_bill' => '1320000','total_paid' => '0','total_due' => '1320000','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '1','category_name' => '0000007','total_bill' => '650000','total_paid' => '0','total_due' => '650000','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '6','category_name' => '0000008','total_bill' => '1800','total_paid' => '1800','total_due' => '0','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '7','category_name' => '0000010','total_bill' => '200','total_paid' => '0','total_due' => '200','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now()),
            array('party_id' => '7','category_name' => '0000011','total_bill' => '10000','total_paid' => '0','total_due' => '10000','income_description' => NULL,'status' => '1','created_at' => now(),'updated_at' => now())
          );

        Income::insert($incomes);
    }
}
