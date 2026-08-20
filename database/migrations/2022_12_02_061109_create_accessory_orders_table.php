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
        Schema::create('accessory_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique(); // input field / auto generate from model
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('accessory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->constrained('parties')->cascadeOnDelete();
            $table->integer('qty_unit');
            $table->double('unit_price');
            $table->double('ttl_amount')->nullable();
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
        Schema::dropIfExists('accessory_orders');
    }
};
