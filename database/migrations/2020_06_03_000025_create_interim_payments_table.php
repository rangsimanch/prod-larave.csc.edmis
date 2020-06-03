<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterimPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('interim_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_period')->nullable();
            $table->string('month');
            $table->string('year');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
