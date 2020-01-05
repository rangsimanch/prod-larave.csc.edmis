<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWbsLevelThreesTable extends Migration
{
    public function up()
    {
        Schema::create('wbs_level_threes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('wbs_level_3_name')->nullable();

            $table->string('wbs_level_3_code')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
