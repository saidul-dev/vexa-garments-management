<?php

namespace Database\Seeders;

use App\Models\Cash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cashes = array(
            array('user_id' => '1','voucher_id' => '1','amount' => '10000000','type' => 'credit','bank_id' => 'petty_cash','cash_type' => 'main cash','date' => '2024-07-23','description' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','voucher_id' => '2','amount' => '2000','type' => 'debit','bank_id' => NULL,'cash_type' => 'employee_salary','date' => '2024-07-23','description' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','voucher_id' => '3','amount' => '5000','type' => 'credit','bank_id' => 'new_party_create','cash_type' => 'customer_payment','date' => '2024-07-24','description' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','voucher_id' => '4','amount' => '20000','type' => 'debit','bank_id' => '1','cash_type' => 'employee_salary','date' => '2024-07-29','description' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '9','voucher_id' => '5','amount' => '2000','type' => 'debit','bank_id' => NULL,'cash_type' => 'balance_withdraw','date' => '2024-08-03','description' => 'Remarks','deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','voucher_id' => '6','amount' => '200000','type' => 'debit','bank_id' => NULL,'cash_type' => 'employee_salary','date' => '2024-08-06','description' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '3','voucher_id' => '7','amount' => '1800','type' => 'credit','bank_id' => NULL,'cash_type' => 'party_payment','date' => '2024-08-15','description' => 'jhg','deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '9','voucher_id' => '8','amount' => '100','type' => 'debit','bank_id' => NULL,'cash_type' => 'balance_withdraw','date' => '2024-08-19','description' => 'Ab','deleted_at' => NULL,'created_at' => now(),'updated_at' => now())
        );

        Cash::insert($cashes);
    }
}
