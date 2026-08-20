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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->longText('order_info')->nullable(); // style, item, color, quantity
            $table->text('cutting')->nullable(); // daily, ttl_cutting, balance
            $table->text('print')->nullable(); // name, daily, total, balance
            $table->text('input_line')->nullable(); // Total Input, TTL Input Balance
            $table->text('output_line')->nullable(); // daily, total, balance
            $table->text('finishing')->nullable(); // daily, total, balance
            $table->text('poly')->nullable(); // daily, total, balance
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('productions');
    }
};
