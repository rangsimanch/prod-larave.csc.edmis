<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWbslevelfoursTable extends Migration
{
    public function up()
    {
        Schema::create('wbslevelfours', function (Blueprint $table) {
            $table->increments('id');

            $table->string('wbs_level_4_name')->nullable();

            $table->string('wbs_level_4_code')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
