<?php

namespace Database\Seeders;

use App\Models\Bookingdetails;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingdetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookingdetails = array(
            array('booking_id' => '1','data' => '{"desc_garments":[null,null],"pantone":[null,null],"body_fab":[null,null],"yarn_count_body":[null,null],"garments_qty_dzn":[null,null],"body_fab_dzn":[null,null],"body_gray_fab":[null,null],"desc_garments_rib":[null,null],"yarn_count_rib":[null,null],"consump_rib_dzn":[null,null],"rib_kg":[null,null],"yarn_count_rib_lycra":[null,null],"receive":[null,null],"balance":[null,null],"gray_body_fab":[null,null],"gray_body_rib":[null,null],"revised":[null,null],"images":[]}','cuff_color' => '[null,null]','collar_size_qty' => '{"40_xs":[null,null],"41_s":[null,null],"42_m":[null,null],"43_l":[null,null],"44_xl":[null,null],"45_xxl":[null,null],"46_3xl":[null,null],"47_4xl":[null,null]}','cuff_solid' => '{"37_l":[null,null],"38_4xl":[null,null],"39_5xl":[null,null],"40_6xl":[null,null]}','created_at' => now(),'updated_at' => now()),
            array('booking_id' => '2','data' => '{"desc_garments":[null],"pantone":[null],"body_fab":[null],"yarn_count_body":[null],"garments_qty_dzn":[null],"body_fab_dzn":[null],"body_gray_fab":[null],"desc_garments_rib":[null],"yarn_count_rib":[null],"consump_rib_dzn":[null],"rib_kg":[null],"yarn_count_rib_lycra":[null],"receive":[null],"balance":[null],"gray_body_fab":[null],"gray_body_rib":[null],"revised":[null],"images":[]}','cuff_color' => '[null]','collar_size_qty' => '{"40_xs":[null],"41_s":[null],"42_m":[null],"43_l":[null],"44_xl":[null],"45_xxl":[null],"46_3xl":[null],"47_4xl":[null]}','cuff_solid' => '{"37_l":[null],"38_4xl":[null],"39_5xl":[null],"40_6xl":[null]}','created_at' => now(),'updated_at' => now()),
            array('booking_id' => '3','data' => '{"desc_garments":[null],"pantone":[null],"body_fab":[null],"yarn_count_body":[null],"garments_qty_dzn":[null],"body_fab_dzn":[null],"body_gray_fab":[null],"desc_garments_rib":[null],"yarn_count_rib":[null],"consump_rib_dzn":[null],"rib_kg":[null],"yarn_count_rib_lycra":[null],"receive":[null],"balance":[null],"gray_body_fab":[null],"gray_body_rib":[null],"revised":[null],"images":["uploads\\/24\\/08\\/1723469809_66ba0ff1f1ad3.jpg"]}','cuff_color' => '[null]','collar_size_qty' => '{"40_xs":[null],"41_s":[null],"42_m":[null],"43_l":[null],"44_xl":[null],"45_xxl":[null],"46_3xl":[null],"47_4xl":[null]}','cuff_solid' => '{"37_l":[null],"38_4xl":[null],"39_5xl":[null],"40_6xl":[null]}','created_at' => now(),'updated_at' => now()),
            array('booking_id' => '4','data' => '{"desc_garments":[null,null,null],"pantone":[null,null,null],"body_fab":[null,null,null],"yarn_count_body":[null,null,null],"garments_qty_dzn":[null,null,null],"body_fab_dzn":[null,null,null],"body_gray_fab":[null,null,null],"desc_garments_rib":[null,null,null],"yarn_count_rib":[null,null,null],"consump_rib_dzn":[null,null,null],"rib_kg":[null,null,null],"yarn_count_rib_lycra":[null,null,null],"receive":[null,null,null],"balance":[null,null,null],"gray_body_fab":[null,null,null],"gray_body_rib":[null,null,null],"revised":[null,null,null],"images":[]}','cuff_color' => '[null,null,null]','collar_size_qty' => '{"40_xs":[null,null,null],"41_s":[null,null,null],"42_m":[null,null,null],"43_l":[null,null,null],"44_xl":[null,null,null],"45_xxl":[null,null,null],"46_3xl":[null,null,null],"47_4xl":[null,null,null]}','cuff_solid' => '{"37_l":[null,null,null],"38_4xl":[null,null,null],"39_5xl":[null,null,null],"40_6xl":[null,null,null]}','created_at' => now(),'updated_at' => now())
          );

        Bookingdetails::insert($bookingdetails);
    }
}
