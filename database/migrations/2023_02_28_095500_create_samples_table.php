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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('consignee');
            $table->longText('header')->nullable();
            $table->longText('styles')->nullable();
            $table->longText('colors')->nullable();
            $table->longText('items')->nullable();
            $table->longText('types')->nullable(); // Sample Type
            $table->longText('sizes')->nullable();
            $table->longText('quantities')->nullable(); // Garments Qty
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
        Schema::dropIfExists('samples');
    }
};
