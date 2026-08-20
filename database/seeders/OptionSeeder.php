<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = array(
            array('key' => 'company','value' => '{"_method":"put","name":"Vishesh Textiles","email":"maantheme@gmail.com","remarks":"Software company in Dhaka","address":"RH Home Center, Level#2, Green Road, Dhaka","website":"https:\\/\\/maanerp.acnoo.com","logo":"assets\\/images\\/logo\\/backend_logo.svg","favicon":"assets\\/images\\/logo\\/favicon.svg"}','status' => '1','created_at' => now(),'updated_at' => now()),
            array('key' => 'company_balance','value' => '0','status' => '1','created_at' => now(),'updated_at' => now())
        );
        Option::insert($options);
    }
}
