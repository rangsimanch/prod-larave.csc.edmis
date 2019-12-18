<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileManagersTable extends Migration
{
    public function up()
    {
        Schema::create('file_managers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('file_name')->nullable();

            $table->string('code')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
