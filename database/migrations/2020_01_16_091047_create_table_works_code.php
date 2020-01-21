<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWorksCode extends Migration
{
    public function up()
    {
        Schema::create('works_codes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();

            $table->string('code')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
