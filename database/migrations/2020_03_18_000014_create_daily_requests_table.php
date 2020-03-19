<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->date('input_date')->nullable();
            $table->date('receive_date')->nullable();
            $table->boolean('acknowledge')->default(0)->nullable();
            $table->date('acknowledge_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
