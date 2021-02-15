<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoqAndWbsFieldsToRfasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfas', function (Blueprint $table) {
            //
            $table->unsignedInteger('boq_id')->nullable();
            $table->foreign('boq_id', 'boq_fk_3097440')->references('id')->on('bo_qs');
            $table->unsignedInteger('wbs_level_one_id')->nullable();
            $table->foreign('wbs_level_one_id', 'wbs_level_one_fk_3097441')->references('id')->on('wbs_level_ones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfas', function (Blueprint $table) {
            //
        });
    }
}
