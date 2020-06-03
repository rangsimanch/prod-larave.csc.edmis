<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsBuildPlansTable extends Migration
{
    public function up()
    {
        Schema::create('as_build_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop_drawing_title')->nullable();
            $table->string('coding_of_shop_drawing')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
