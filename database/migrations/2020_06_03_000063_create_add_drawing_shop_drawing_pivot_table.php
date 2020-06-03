<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddDrawingShopDrawingPivotTable extends Migration
{
    public function up()
    {
        Schema::create('add_drawing_shop_drawing', function (Blueprint $table) {
            $table->unsignedInteger('shop_drawing_id');
            $table->foreign('shop_drawing_id', 'shop_drawing_id_fk_1566038')->references('id')->on('shop_drawings')->onDelete('cascade');
            $table->unsignedInteger('add_drawing_id');
            $table->foreign('add_drawing_id', 'add_drawing_id_fk_1566038')->references('id')->on('add_drawings')->onDelete('cascade');
        });
    }
}
