<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('variation_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_of_vo')->nullable();
            $table->string('work_title')->nullable();
            $table->date('operation_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
