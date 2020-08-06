<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToWbsLevelThreesTable extends Migration
{
    public function up()
    {
        Schema::table('wbs_level_threes', function (Blueprint $table) {
            $table->unsignedInteger('wbs_level_2_id')->nullable();
            $table->foreign('wbs_level_2_id', 'wbs_level_2_fk_1874497')->references('id')->on('bo_qs');
        });
    }
}
