<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRequestForInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_for_inspections', function (Blueprint $table) {
            //
            $table->decimal('amount', 15, 2)->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->foreign('item_id', 'item_fk_1760121')->references('id')->on('boq_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_for_inspections', function (Blueprint $table) {
            //
        });
    }
}
