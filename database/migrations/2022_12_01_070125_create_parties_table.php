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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable(); // buyer / supplier / customer
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete(); // Who created this party
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->cascadeOnDelete();
            $table->string('address')->nullable();
            $table->double('total_bill')->default(0);
            $table->double('advance_amount')->default(0);
            $table->double('due_amount')->default(0);
            $table->double('pay_amount')->default(0);
            $table->double('balance')->default(0);
            $table->string('receivable_type')->nullable(); // Cash / Bank
            $table->double('opening_balance')->nullable();
            $table->string('opening_balance_type')->nullable(); // advance_payment / due_bill
            $table->text('meta')->nullable(); // bank_id
            $table->string('remarks')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('parties');
    }
};
