<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvisionalSumsTable extends Migration
{
    public function up()
    {
        Schema::create('provisional_sums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bill_no')->nullable();
            $table->string('item_no')->nullable();
            $table->string('name_of_ps')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
