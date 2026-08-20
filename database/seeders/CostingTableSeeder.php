<?php

namespace Database\Seeders;

use App\Models\Costing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $costings = array(
            array('user_id' => '1','order_id' => '2','buying_commission' => NULL,'order_info' => '{"style":"7100","shipment_date":"2024-07-22","qty":"90","unit_price":"90","lc":"8100"}','yarn_desc' => '{"items":["CAJA"],"composition":["CAJA"],"type":["1"],"qty":["10"],"unit":["15"],"price":["15"],"total":["150.00"],"grand_total":[null],"payment":["0"],"remarks":[null]}','yarn_total' => '150','knitting_desc' => '{"items":["CAJA 2"],"composition":["CAJA 2"],"type":["5"],"qty":["15"],"unit":["6"],"price":["85"],"total":["1275.00"],"grand_total":[null],"payment":["0"],"remarks":[null]}','knitting_total' => '1275','dyeing_desc' => '{"items":["CAJA 3"],"composition":["CAJA 3"],"type":["2"],"qty":["13"],"unit":["2"],"price":["4"],"total":["52.00"],"grand_total":[null],"payment":["0"],"remarks":[null]}','dyeing_total' => '52','print_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0.00"],"grand_total":[null],"payment":[null],"remarks":[null]}','print_total' => '0.00','trim_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0.00"],"grand_total":[null],"payment":[null],"remarks":[null]}','trim_total' => '0','commercial_qty' => NULL,'commercial_unit' => NULL,'commercial_price' => NULL,'commercial_total' => '0','cm_cost_composition' => NULL,'cm_cost_qty' => NULL,'cm_cost_unit' => NULL,'cm_cost_price' => NULL,'cm_cost_total' => '0','grand_total' => '1477','grand_total_in_dzn' => '123.08','status' => '2','reason' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '3','order_id' => '3','buying_commission' => NULL,'order_info' => '{"style":"CARGO NR232","shipment_date":"2024-09-20","qty":"20000","unit_price":"5.50","lc":"110000"}','yarn_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0"],"grand_total":[null],"payment":[null],"remarks":[null]}','yarn_total' => '0','knitting_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0"],"grand_total":[null],"payment":[null],"remarks":[null]}','knitting_total' => '0','dyeing_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0"],"grand_total":[null],"payment":[null],"remarks":[null]}','dyeing_total' => '0','print_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0"],"grand_total":[null],"payment":[null],"remarks":[null]}','print_total' => '0','trim_desc' => '{"items":[null],"composition":[null],"type":[null],"qty":[null],"unit":[null],"price":[null],"total":["0"],"grand_total":[null],"payment":[null],"remarks":[null]}','trim_total' => '0','commercial_qty' => NULL,'commercial_unit' => NULL,'commercial_price' => NULL,'commercial_total' => '0','cm_cost_composition' => NULL,'cm_cost_qty' => NULL,'cm_cost_unit' => NULL,'cm_cost_price' => NULL,'cm_cost_total' => '0','grand_total' => '0','grand_total_in_dzn' => '0','status' => '1','reason' => NULL,'created_at' => now(),'updated_at' => now())
          );

        Costing::insert($costings);
    }
}
