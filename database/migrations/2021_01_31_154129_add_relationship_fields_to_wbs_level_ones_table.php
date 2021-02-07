<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToWbsLevelOnesTable extends Migration
{
    public function up()
    {
        Schema::table('wbs_level_ones', function (Blueprint $table) {
            $table->unsignedInteger('wbs_lv_1_id')->nullable();
            $table->foreign('wbs_lv_1_id', 'wbs_lv_1_fk_3004851')->references('id')->on('wbs_level_fives');
        });
    }
}
