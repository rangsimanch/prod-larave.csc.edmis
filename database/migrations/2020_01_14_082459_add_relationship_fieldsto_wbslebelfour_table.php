<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldstoWbslebelfourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wbslevelfours', function (Blueprint $table) {
            $table->unsignedInteger('wbs_level_three_id')->nullable();
            $table->foreign('wbs_level_three_id', 'wbs_level_three_fk_868220')->references('id')->on('wbs_level_threes');
        });
    }
}
