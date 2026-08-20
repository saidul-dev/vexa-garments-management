<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete(); // Creator
            $table->string('style');
            $table->string('shipment_date');
            $table->string('color')->nullable();
            $table->string('item')->nullable();
            $table->string('size')->nullable();
            $table->string('qty')->default(0);
            $table->double('total_cm', 15, 3)->default(0); // Total cost of making in USD
            $table->double('total_sale', 15, 3)->default(0); // Total Profit In USD
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_details');
    }
};
