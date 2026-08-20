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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('bank_from')->nullable()->constrained('banks')->cascadeOnDelete(); // Money sender.
            $table->foreignId('bank_to')->nullable()->constrained('banks')->cascadeOnDelete(); // Money receiver.
            $table->double('amount')->default(0);
            $table->string('transfer_type')->nullable(); // bank_to_bank, bank_to_cash, cash_to_bank, adjust_bank
            $table->string('adjust_type')->nullable(); // debit, credit, others
            $table->date('date')->default(today());
            $table->text('note')->nullable();
            $table->text('meta')->nullable();
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
        Schema::dropIfExists('transfers');
    }
};
