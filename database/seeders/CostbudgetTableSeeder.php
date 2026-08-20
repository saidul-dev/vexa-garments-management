<?php

namespace Database\Seeders;

use App\Models\Costbudget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostbudgetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $costbudgets = array(
            array('user_id' => '1','order_id' => '2','order_info' => '{"style":"7100","color":"black","shipment_date":"2024-07-22","qty":"90","unit_price":"90","lc":"8100"}','pre_cost_date' => '2024-07-24','post_cost_date' => '2024-07-25','image' => NULL,'yarn_desc' => '{"fab_desc":["CARTON CORRUGADO"],"supplier_name":["CARTON"],"yarn_count":["5"],"unit_price":["15"],"consumption":["0"],"w":["0"],"total_qty":["2"],"total_cost":["30.000"],"pre_cost":["0.370"]}','yarn_cost' => '30','yarn_qty' => '2','knitting_desc' => '{"fab_desc":["CINTA"],"supplier_name":["CINTA"],"yarn_count":["8"],"unit_price":["10"],"consumption":["0"],"w":["0"],"total_qty":["5"],"total_cost":["50.000"],"pre_cost":["0.617"]}','knitting_cost' => '50','knitting_qty' => '5','dfa_desc' => '{"fab_desc":["RESISTOL"],"supplier_name":["RESISTOL"],"yarn_count":["10"],"unit_price":["20"],"consumption":["0"],"w":["0"],"total_qty":["15"],"total_cost":["300.000"],"pre_cost":["3.704"]}','dfa_cost' => '300','dfa_qty' => '15','fabric_cost' => '380','fabric_qty' => '22','accessories_desc' => '{"accessories_des":[null],"supplier_name":[null],"unit_price":["0"],"unit_number":[null],"consumption":[null],"w":[null],"total_qty":["0"],"total_cost":["0.000"],"pre_cost":["0.000"]}','accessories_cost' => '0','accessories_qty' => '0','total_making_cost' => '380','total_making_pre_cost' => '4.691','finance_value' => '0','finance_cost' => '0','finance_pre_cost' => '0','deferred_value' => '0','deferred_cost' => '0','deferred_pre_cost' => '0','other_cost' => NULL,'grand_cost' => '380','pre_cost_desc' => '{"yarn":"0.37","knitting":"0.617","dfa":"3.704","fabric":"4.691","accessories":"0","grand":"4.691"}','factory_cm_cost' => '380','factory_cm_pre_cost' => '4.691','total_expense_cost' => '380','total_expense_pre_cost' => '4.691','net_earning_cost' => '7720','net_earning_pre_cost' => '95.309','status' => '2','meta' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','order_id' => '3','order_info' => '{"style":"CARGO NR232","color":"TIGER PRINT","shipment_date":"2024-09-20","qty":"20000","unit_price":"5.50","lc":"110000"}','pre_cost_date' => '2024-07-25','post_cost_date' => '2024-08-31','image' => NULL,'yarn_desc' => '{"fab_desc":["100%COTTON TWILL AOP"],"supplier_name":["UNITED TEX"],"yarn_count":["168X70"],"unit_price":["3.5"],"consumption":[null],"w":["3%"],"total_qty":["15000"],"total_cost":["52500.000"],"pre_cost":["47.727"]}','yarn_cost' => '52500','yarn_qty' => '15000','knitting_desc' => '{"fab_desc":[null],"supplier_name":[null],"yarn_count":[null],"unit_price":["0"],"consumption":[null],"w":[null],"total_qty":["0"],"total_cost":["0.000"],"pre_cost":["0.000"]}','knitting_cost' => '0','knitting_qty' => '0','dfa_desc' => '{"fab_desc":[null],"supplier_name":[null],"yarn_count":[null],"unit_price":["0"],"consumption":["0"],"w":[null],"total_qty":["0"],"total_cost":["0.000"],"pre_cost":["0.000"]}','dfa_cost' => '0','dfa_qty' => '0','fabric_cost' => '52500','fabric_qty' => '15000','accessories_desc' => '{"accessories_des":["BATTON"],"supplier_name":["DF ACCESSORIES"],"unit_price":["0.10"],"unit_number":["15000"],"consumption":[null],"w":["1%"],"total_qty":["15000"],"total_cost":["1500.000"],"pre_cost":["1.364"]}','accessories_cost' => '1500','accessories_qty' => '15000','total_making_cost' => '54000','total_making_pre_cost' => '49.090999999999994','finance_value' => '0.6','finance_cost' => '660','finance_pre_cost' => '0.6','deferred_value' => '0','deferred_cost' => '0','deferred_pre_cost' => '0','other_cost' => NULL,'grand_cost' => '54660','pre_cost_desc' => '{"yarn":"47.727","knitting":"0","dfa":"0","fabric":"47.727","accessories":"1.364","grand":"49.690999999999995"}','factory_cm_cost' => '54000','factory_cm_pre_cost' => '49.090999999999994','total_expense_cost' => '54660','total_expense_pre_cost' => '49.691','net_earning_cost' => '55340','net_earning_pre_cost' => '50.309','status' => '2','meta' => NULL,'created_at' => now(),'updated_at' => now())
          );

        Costbudget::insert($costbudgets);
    }
}
