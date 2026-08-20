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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Create Id
            $table->integer('year'); // Create Id
            $table->string('month'); // Create Id
            $table->string('payment_method'); // Cash, Bank, Cheque
            $table->double('amount'); // Salary 
            $table->double('due_salary')->default(0); // Due Salary 
            $table->string('notes')->nullable(); // Salary 
            $table->longText('meta')->nullable(); // Cheque no, Issue Date 
            $table->softDeletes();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
