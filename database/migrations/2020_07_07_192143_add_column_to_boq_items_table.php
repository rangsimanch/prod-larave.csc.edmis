<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBoqItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boq_items', function (Blueprint $table) {
            //
            $table->string('unit')->nullable();
            $table->float('quantity', 15, 2)->nullable();
            $table->float('unit_rate', 15, 2)->nullable();
            $table->float('factor_f', 15, 2)->nullable();
            $table->float('unit_rate_x_ff', 15, 2)->nullable();
            $table->float('total_amount', 15, 2)->nullable();
            $table->longText('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boq_items', function (Blueprint $table) {
            //
        });
    }
}
