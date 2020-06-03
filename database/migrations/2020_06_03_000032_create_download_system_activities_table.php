<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadSystemActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('download_system_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity_title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
