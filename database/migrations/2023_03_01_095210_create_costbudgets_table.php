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
        Schema::create('costbudgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->text('order_info')->nullable();
            $table->date('pre_cost_date');
            $table->date('post_cost_date');
            $table->string('image')->nullable();
            $table->longText('yarn_desc')->nullable(); // Supplier Name	Yarn Count	Unit Price ($)	Consumptions (Kg/Dz)	W%	Total Qty (Kg.)	Total Cost	Pre-Cost%
            $table->double('yarn_cost')->default(0);
            $table->double('yarn_qty')->default(0);
            $table->longText('knitting_desc')->nullable();
            $table->double('knitting_cost')->default(0);
            $table->double('knitting_qty')->default(0);
            $table->longText('dfa_desc')->nullable();
            $table->double('dfa_cost')->default(0); // Total Dyeing+Finishing, AOP Cost
            $table->double('dfa_qty')->default(0); // Total Dyeing+Finishing, AOP Quantity
            $table->double('fabric_cost')->default(0); // Total Fabric Cost
            $table->double('fabric_qty')->default(0); // Total Fabric Quantity
            $table->longText('accessories_desc')->nullable();
            $table->double('accessories_cost')->default(0); // same as yarn desc
            $table->double('accessories_qty')->default(0);

            $table->double('total_making_cost')->default(0); // total fabric + accessories cost
            $table->double('total_making_pre_cost')->default(0); // total fabric + accessories pre cost
            $table->double('finance_value')->default(0)->nullable();
            $table->double('finance_cost')->default(0);
            $table->double('finance_pre_cost')->default(0);
            $table->double('deferred_value')->default(0)->nullable();
            $table->double('deferred_cost')->default(0);
            $table->double('deferred_pre_cost')->default(0);
            $table->double('other_cost')->default(0)->nullable();
            $table->double('grand_cost')->default(0);
            $table->longText('pre_cost_desc')->nullable(); // Pre-Cost%

            $table->double('factory_cm_cost')->default(0);
            $table->double('factory_cm_pre_cost')->default(0);
            $table->double('total_expense_cost')->default(0);
            $table->double('total_expense_pre_cost')->default(0);
            $table->double('net_earning_cost')->default(0);
            $table->double('net_earning_pre_cost')->default(0);
            $table->integer('status')->default(1); // 0 = reject, 1 = pending, 2 = approved.
            $table->text('meta')->nullable();
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
        Schema::dropIfExists('costbudgets');
    }
};
