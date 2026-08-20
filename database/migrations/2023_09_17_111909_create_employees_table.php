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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('designation_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->double('salary', 10, 3)->default(0);
            $table->string('employee_type')->nullable(); // Part time / Full time
            $table->date('join_date')->nullable(); // Date of joining
            $table->date('birth_date')->nullable(); // Date of joining
            $table->boolean('status')->default(1);
            $table->text('meta')->nullable(); // Nid/Passport image.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
