<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookings = array(
            array('user_id' => '1','order_id' => '1','booking_no' => 'BK-0001','composition' => NULL,'booking_date' => '2024-07-22','meta' => '{"process_loss":null,"other_fabric":null,"rib":null,"collar":null}','header' => '{"collar_size_qty_40":"40X7.5","collar_size_qty_41":"41X7.5","collar_size_qty_42":"42X7.5","collar_size_qty_43":"43X7.5","collar_size_qty_44":"44X7.5","collar_size_qty_45":"45X7.5","collar_size_qty_46":"46X7.5","collar_size_qty_47":"47X7.5","cuff_solid_l":"Qty.XS-L","cuff_solid_4xl":"Qty.XL-4XL","cuff_solid_5xl":"Qty.XS-5XL","cuff_solid_6xl":"Qty.XL-6XL","collar_size_qty_xs":"XS","collar_size_qty_s":"S","collar_size_qty_m":"M","collar_size_qty_l":"L","collar_size_qty_xl":"XL","collar_size_qty_xxl":"XXL","collar_size_qty_3xl":"3XL","collar_size_qty_4xl":"4XL","cuff_solid_37":"37X.3.5 CM","cuff_solid_38":"40X.3.5 CM","cuff_solid_39":"37X.3.5 CM","cuff_solid_40":"40X.3.5 CM"}','status' => '2','reason' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '2','order_id' => '3','booking_no' => 'BK-0002','composition' => '100%COTTON TWILL AOP','booking_date' => '2024-07-25','meta' => '{"process_loss":null,"other_fabric":null,"rib":null,"collar":null}','header' => '{"collar_size_qty_40":"40X7.5","collar_size_qty_41":"41X7.5","collar_size_qty_42":"42X7.5","collar_size_qty_43":"43X7.5","collar_size_qty_44":"44X7.5","collar_size_qty_45":"45X7.5","collar_size_qty_46":"46X7.5","collar_size_qty_47":"47X7.5","cuff_solid_l":"Qty.XS-L","cuff_solid_4xl":"Qty.XL-4XL","cuff_solid_5xl":"Qty.XS-5XL","cuff_solid_6xl":"Qty.XL-6XL","collar_size_qty_xs":"XS","collar_size_qty_s":"S","collar_size_qty_m":"M","collar_size_qty_l":"L","collar_size_qty_xl":"XL","collar_size_qty_xxl":"XXL","collar_size_qty_3xl":"3XL","collar_size_qty_4xl":"4XL","cuff_solid_37":"37X.3.5 CM","cuff_solid_38":"40X.3.5 CM","cuff_solid_39":"37X.3.5 CM","cuff_solid_40":"40X.3.5 CM"}','status' => '2','reason' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '7','booking_no' => 'BK-0003','composition' => NULL,'booking_date' => '2024-08-12','meta' => '{"process_loss":null,"other_fabric":null,"rib":null,"collar":null}','header' => '{"collar_size_qty_40":"40X7.5","collar_size_qty_41":"41X7.5","collar_size_qty_42":"42X7.5","collar_size_qty_43":"43X7.5","collar_size_qty_44":"44X7.5","collar_size_qty_45":"45X7.5","collar_size_qty_46":"46X7.5","collar_size_qty_47":"47X7.5","cuff_solid_l":"Qty.XS-L","cuff_solid_4xl":"Qty.XL-4XL","cuff_solid_5xl":"Qty.XS-5XL","cuff_solid_6xl":"Qty.XL-6XL","collar_size_qty_xs":"XS","collar_size_qty_s":"S","collar_size_qty_m":"M","collar_size_qty_l":"L","collar_size_qty_xl":"XL","collar_size_qty_xxl":"XXL","collar_size_qty_3xl":"3XL","collar_size_qty_4xl":"4XL","cuff_solid_37":"37X.3.5 CM","cuff_solid_38":"40X.3.5 CM","cuff_solid_39":"37X.3.5 CM","cuff_solid_40":"40X.3.5 CM"}','status' => '2','reason' => NULL,'created_at' => now(),'updated_at' => now()),
            array('user_id' => '1','order_id' => '5','booking_no' => 'BK-0004','composition' => NULL,'booking_date' => '2024-08-14','meta' => '{"process_loss":null,"other_fabric":null,"rib":null,"collar":null}','header' => '{"collar_size_qty_40":"40X7.5","collar_size_qty_41":"41X7.5","collar_size_qty_42":"42X7.5","collar_size_qty_43":"43X7.5","collar_size_qty_44":"44X7.5","collar_size_qty_45":"45X7.5","collar_size_qty_46":"46X7.5","collar_size_qty_47":"47X7.5","cuff_solid_l":"Qty.XS-L","cuff_solid_4xl":"Qty.XL-4XL","cuff_solid_5xl":"Qty.XS-5XL","cuff_solid_6xl":"Qty.XL-6XL","collar_size_qty_xs":"XS","collar_size_qty_s":"S","collar_size_qty_m":"M","collar_size_qty_l":"L","collar_size_qty_xl":"XL","collar_size_qty_xxl":"XXL","collar_size_qty_3xl":"3XL","collar_size_qty_4xl":"4XL","cuff_solid_37":"37X.3.5 CM","cuff_solid_38":"40X.3.5 CM","cuff_solid_39":"37X.3.5 CM","cuff_solid_40":"40X.3.5 CM"}','status' => '2','reason' => NULL,'created_at' => now(),'updated_at' => now())
          );

        Booking::insert($bookings);

    }
}
