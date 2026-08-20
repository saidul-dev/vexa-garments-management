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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('booking_no')->nullable();
            $table->string('composition')->nullable();
            $table->date('booking_date');
            $table->longText('meta')->nullable(); // Yearn Count // Fabric Consumption // Finish Dia // Finish Fabric Qty // Process Loss // Grey Fabric Qty // Others Fabric // Fabrication // Rib // Collar // Prepared By
            $table->longText('header')->nullable(); // booking details table header
            $table->tinyInteger('status')->default(1); // reject = 0, pending = 1, approved = 2
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('bookings');
    }
};
