<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopDrawingsTable extends Migration
{
    public function up()
    {
        Schema::create('shop_drawings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop_drawing_title')->nullable();
            $table->string('coding_of_shop_drawing')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
