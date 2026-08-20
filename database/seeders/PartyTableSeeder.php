<?php

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartyTableSeeder extends Seeder
{
    public function run()
    {
        $parties = array(
            array('name' => 'LC Waikiki','type' => 'buyer','creator_id' => '1','user_id' => '11','currency_id' => NULL,'address' => NULL,'total_bill' => '650000','advance_amount' => '0','due_amount' => '650000','pay_amount' => '0','balance' => '0','receivable_type' => NULL,'opening_balance' => NULL,'opening_balance_type' => NULL,'meta' => '{"bank_id":null}','remarks' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Line 1','type' => 'customer','creator_id' => '1','user_id' => '12','currency_id' => NULL,'address' => NULL,'total_bill' => '0','advance_amount' => '0','due_amount' => '0','pay_amount' => '0','balance' => '0','receivable_type' => NULL,'opening_balance' => NULL,'opening_balance_type' => NULL,'meta' => '{"bank_id":null}','remarks' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Line-2','type' => 'customer','creator_id' => '1','user_id' => '13','currency_id' => NULL,'address' => NULL,'total_bill' => '8490','advance_amount' => '0','due_amount' => '8490','pay_amount' => '0','balance' => '0','receivable_type' => NULL,'opening_balance' => NULL,'opening_balance_type' => NULL,'meta' => '{"bank_id":null}','remarks' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'COATS','type' => 'supplier','creator_id' => '1','user_id' => '14','currency_id' => NULL,'address' => NULL,'total_bill' => '55438','advance_amount' => '0','due_amount' => '55438','pay_amount' => '0','balance' => '0','receivable_type' => NULL,'opening_balance' => NULL,'opening_balance_type' => NULL,'meta' => '{"bank_id":null}','remarks' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'RGB Textiles','type' => 'customer','creator_id' => '1','user_id' => '17','currency_id' => NULL,'address' => NULL,'total_bill' => '1320000','advance_amount' => '4900','due_amount' => '1320000','pay_amount' => '0','balance' => '4900','receivable_type' => 'cash','opening_balance' => '5000','opening_balance_type' => 'advance_payment','meta' => '{"bank_id":null}','remarks' => NULL,'deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'NR','type' => 'buyer','creator_id' => '2','user_id' => '18','currency_id' => NULL,'address' => 'afafsfs','total_bill' => '111800','advance_amount' => '10000','due_amount' => '110000','pay_amount' => '1800','balance' => '10000','receivable_type' => 'bank','opening_balance' => '10000','opening_balance_type' => 'advance_payment','meta' => '{"bank_id":"1"}','remarks' => 'LC Payment','deleted_at' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => '000-B2','type' => 'buyer','creator_id' => '1','user_id' => '19','currency_id' => NULL,'address' => '123456','total_bill' => '10200','advance_amount' => '0','due_amount' => '11200','pay_amount' => '0','balance' => '0','receivable_type' => NULL,'opening_balance' => '1000','opening_balance_type' => 'due_bill','meta' => '{"bank_id":null}','remarks' => 'BIG PRODUCT','deleted_at' => NULL,'created_at' => now(),'updated_at' => now())
          );

        Party::insert($parties);
    }
}
