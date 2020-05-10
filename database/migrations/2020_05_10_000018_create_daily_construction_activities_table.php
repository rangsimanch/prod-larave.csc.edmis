<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyConstructionActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_construction_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_title')->nullable();
            $table->date('operation_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
