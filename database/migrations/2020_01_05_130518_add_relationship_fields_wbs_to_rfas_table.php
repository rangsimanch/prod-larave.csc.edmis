<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsWbsToRfasTable extends Migration
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
            $table->unsignedInteger('wbs_level_3_id')->nullable();

            $table->foreign('wbs_level_3_id', 'wbs_level_3_fk_823990')->references('id')->on('wbs_level_threes');

            $table->unsignedInteger('wbs_level_4_id')->nullable();

            $table->foreign('wbs_level_4_id', 'wbs_level_4_fk_823991')->references('id')->on('wbslevelfours');
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
