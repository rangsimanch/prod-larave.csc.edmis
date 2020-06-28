<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipColumnToRequestForInspectionsTable extends Migration
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
            $table->unsignedInteger('bill_id')->nullable();
            $table->foreign('bill_id', 'bill_fk_1722432')->references('id')->on('bo_qs');
            $table->unsignedInteger('wbs_level_1_id')->nullable();
            $table->foreign('wbs_level_1_id', 'wbs_level_1_fk_1722433')->references('id')->on('wbs_level_ones');
            $table->unsignedInteger('wbs_level_2_id')->nullable();
            $table->foreign('wbs_level_2_id', 'wbs_level_2_fk_1722434')->references('id')->on('wbs_level_twos');
            $table->unsignedInteger('wbs_level_3_id')->nullable();
            $table->foreign('wbs_level_3_id', 'wbs_level_3_fk_1722435')->references('id')->on('wbs_level_threes');
            $table->unsignedInteger('wbs_level_4_id')->nullable();
            $table->foreign('wbs_level_4_id', 'wbs_level_4_fk_1722436')->references('id')->on('wbslevelfours');
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
