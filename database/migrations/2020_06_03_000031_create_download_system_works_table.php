<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadSystemWorksTable extends Migration
{
    public function up()
    {
        Schema::create('download_system_works', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
