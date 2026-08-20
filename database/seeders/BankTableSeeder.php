<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = array(
            array('user_id' => 1,'holder_name' => 'Shahidul Islam','bank_name' => 'City Bank','account_number' => '0123532201','branch_name' => 'Farmgate','routing_number' => '555554444','balance' => 0,'created_at' => now(),'updated_at' => now()),
            array('user_id' => 1,'holder_name' => 'Safiull Alam','bank_name' => 'DBBL','account_number' => '02556353221','branch_name' => 'Bhuighar','routing_number' => '2222255555','balance' => 0,'created_at' => now(),'updated_at' => now())
        );
        
        Bank::insert($banks);
    }
}
