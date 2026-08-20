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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no'); // Auto generate from model
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('party_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('expense_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('income_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type'); // debit / credit / order_invoice / employee_salary
            $table->date('date')->nullable();
            $table->double('prev_balance')->default(0); // Previous company balance
            $table->double('current_balance')->default(0); // Current company balance
            $table->string('payment_method')->nullable(); // Cash / Bank / Cheque / Party Balance
            $table->string('voucher_no')->nullable();
            $table->boolean('status')->default(1); // The status will be 0 if this voucher is in the cheque.
            $table->string('bill_type')->nullable(); // Advance / Due bill payment / Others Credit
            $table->double('bill_amount')->default(0); // Total bill (It's not using now, we will use it later.)
            $table->double('amount'); // Paid / Received Amount
            $table->boolean('is_profit')->default(0); // If it is 1 = Will check with order payment from party.;
            $table->string('particulars')->nullable();
            $table->string('remarks')->nullable();
            $table->text('meta')->nullable(); // (Cash/Bank/Cheque) -> Details infos by json. // Bill Amount, Due Amount
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
        Schema::dropIfExists('vouchers');
    }
};
