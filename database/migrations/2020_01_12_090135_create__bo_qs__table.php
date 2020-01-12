<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoQsTable extends Migration
{
    public function up()
    {
        Schema::create('bo_qs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();

            $table->string('code')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
