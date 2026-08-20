<?php

namespace Database\Seeders;

use App\Models\Sample;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $samples = array(
            array('user_id' => '3','order_id' => '5','consignee' => 'fefe','header' => '{"size_xs":"XS","size_s":"S","size_m":"M","size_l":"L","size_xl":"XL","size_xxl":"XXL","size_3xl":"3XL","size_4xl":"4XL"}','styles' => '["POLO","jkjkj","feiewjfmlwef"]','colors' => '["Black","white","red"]','items' => '["fsfjkefef","null","null"]','types' => '[null,null,null]','sizes' => '{"xs":[null,null,null],"s":[null,null,null],"m":[null,null,null],"l":[null,null,null],"xl":[null,null,null],"xxl":[null,null,null],"3xl":[null,null,null],"4xl":[null,null,null]}','quantities' => '["10000","500","5000"]','status' => '1','reason' => NULL,'created_at' => now(),'updated_at' => now())
        );

        Sample::insert($samples);
    }
}
