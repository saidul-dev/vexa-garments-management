<?php

namespace Database\Seeders;

use App\Models\Production;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productions = array(
            array('user_id' => '1','order_id' => '2','order_info' => '{"style":["7100"],"item":["null"],"color":["black"],"qty":["90"]}','cutting' => '{"daily":null,"ttl_cutting":null,"balance":null}','print' => '{"today_send":null,"ttl_send":null,"received":null,"balance":null}','input_line' => '{"name":null,"daily":null,"total":null,"balance":null}','output_line' => '{"daily":null,"total":null,"balance":null}','finishing' => '{"daily_receive":null,"total":null,"balance":null}','poly' => '{"daily":null,"total":null,"balance":null}','remarks' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','order_id' => '3','order_info' => '{"style":["CARGO NR232"],"item":["LADIES LONG PANT"],"color":["TIGER PRINT"],"qty":["20000"]}','cutting' => '{"daily":"500","ttl_cutting":null,"balance":null}','print' => '{"today_send":null,"ttl_send":null,"balance":null,"received":null}','input_line' => '{"name":"Line 3","daily":"500","total":null,"balance":null}','output_line' => '{"daily":null,"total":null,"balance":null}','finishing' => '{"daily_receive":null,"total":null,"balance":null}','poly' => '{"daily":null,"total":null,"balance":null}','remarks' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '7','order_info' => '{"style":["POLO"],"item":["null"],"color":["null"],"qty":["10000"]}','cutting' => '{"daily":"10000","ttl_cutting":null,"balance":null}','print' => '{"today_send":null,"ttl_send":null,"balance":null,"received":null}','input_line' => '{"name":"Line 1","daily":"2000","total":"10000","balance":null}','output_line' => '{"daily":"500","total":null,"balance":null}','finishing' => '{"daily_receive":null,"total":null,"balance":null}','poly' => '{"daily":null,"total":null,"balance":null}','remarks' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '7','order_info' => '{"style":["POLO"],"item":["null"],"color":["null"],"qty":["10000"]}','cutting' => '{"daily":null,"ttl_cutting":null,"balance":null}','print' => '{"today_send":null,"ttl_send":null,"received":null,"balance":null}','input_line' => '{"name":"line 2","daily":null,"total":"5000","balance":null}','output_line' => '{"daily":"1000","total":null,"balance":null}','finishing' => '{"daily_receive":null,"total":null,"balance":null}','poly' => '{"daily":null,"total":null,"balance":null}','remarks' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','order_id' => '7','order_info' => '{"style":["POLO"],"item":["null"],"color":["null"],"qty":["10000"]}','cutting' => '{"daily":"50","ttl_cutting":null,"balance":null}','print' => '{"today_send":null,"ttl_send":null,"received":null,"balance":null}','input_line' => '{"name":null,"daily":null,"total":null,"balance":null}','output_line' => '{"daily":"50","total":null,"balance":null}','finishing' => '{"daily_receive":"50","total":null,"balance":null}','poly' => '{"daily":null,"total":null,"balance":null}','remarks' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '11','order_info' => '{"style":["12"],"item":["t10"],"color":["blak"],"qty":["10"]}','cutting' => '{"daily":"10","ttl_cutting":"100","balance":null}','print' => '{"today_send":null,"ttl_send":null,"received":null,"balance":null}','input_line' => '{"name":null,"daily":null,"total":null,"balance":null}','output_line' => '{"daily":null,"total":null,"balance":null}','finishing' => '{"daily_receive":null,"total":null,"balance":null}','poly' => '{"daily":null,"total":null,"balance":null}','remarks' => NULL,'created_at' => now(),'updated_at' => now())
          );

          Production::insert($productions);
    }
}
