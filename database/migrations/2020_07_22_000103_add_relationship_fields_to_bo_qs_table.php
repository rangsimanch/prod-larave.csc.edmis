<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToBoQsTable extends Migration
{
    public function up()
    {
        Schema::table('bo_qs', function (Blueprint $table) {
            $table->unsignedInteger('wbs_lv_1_id')->nullable();
            $table->foreign('wbs_lv_1_id', 'wbs_lv_1_fk_1874496')->references('id')->on('wbs_level_ones');
        });
    }
}
