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
        Schema::create('costings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('buying_commission')->nullable();
            $table->text('order_info')->nullable();
            $table->longText('yarn_desc')->nullable(); // ITEMS DETAILS, YARN COMPOSITION & COUNT,	TYPE, UNIT, Payment,Remarks
            $table->double('yarn_total')->nullable(); // Yarn grand total

            $table->longText('knitting_desc')->nullable(); // same as yann_desc
            $table->double('knitting_total')->nullable(); // Knitting grand total

            $table->longText('dyeing_desc')->nullable();
            $table->double('dyeing_total')->nullable(); // Dyeing grand total

            $table->longText('print_desc')->nullable();
            $table->longText('print_total')->nullable(); // Print grand total

            $table->longText('trim_desc')->nullable();
            $table->double('trim_total')->nullable(); // Trim grand total

            $table->double('commercial_qty')->nullable();
            $table->string('commercial_unit')->nullable();
            $table->double('commercial_price')->nullable();
            $table->double('commercial_total')->nullable();

            $table->string('cm_cost_composition')->nullable();
            $table->double('cm_cost_qty')->nullable();
            $table->string('cm_cost_unit')->nullable();
            $table->double('cm_cost_price')->nullable();
            $table->double('cm_cost_total')->nullable();

            $table->double('grand_total')->nullable();
            $table->double('grand_total_in_dzn')->nullable();
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
        Schema::dropIfExists('costings');
    }
};
