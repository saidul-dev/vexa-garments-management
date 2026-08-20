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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->constrained('parties')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('merchandiser_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained('banks')->cascadeOnDelete();
            $table->string('order_no')->unique(); // input field / auto generate from model
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('department')->nullable();
            $table->string('fabrication')->nullable();
            $table->string('gsm')->nullable();
            $table->string('yarn_count')->nullable();
            $table->string('shipment_mode')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('year')->nullable();
            $table->string('season')->nullable();
            $table->string('description')->nullable();
            $table->longText('meta')->nullable(); // pentone , reason
            $table->longText('invoice_details')->nullable(); // consignee, contact_date, expire_date, negotiation, loading , discharge, remarks
            $table->double('lc');
            $table->tinyInteger('status')->default(1); // reject = 0, pending = 1, approved = 2 , completed = 3
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
        Schema::dropIfExists('orders');
    }
};
