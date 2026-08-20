<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookingdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->longText('data')->nullable(); // po, desc_garments, images, pantone, body_fab, yarn_count_body, garments_qty_dzn, body_fab_dzn, desc_garments_rib, yarn_count_rib, consump_rib_dzn, rib_kg, yarn_count_rib_lycra, receive, balance, gray_body_fab, gray_body_rib, revised.
            $table->text('cuff_color')->nullable();
            $table->longText('collar_size_qty')->nullable();
            $table->longText('cuff_solid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookingdetails');
    }
};
