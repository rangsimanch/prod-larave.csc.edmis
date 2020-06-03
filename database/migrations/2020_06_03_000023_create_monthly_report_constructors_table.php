<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyReportConstructorsTable extends Migration
{
    public function up()
    {
        Schema::create('monthly_report_constructors', function (Blueprint $table) {
            $table->increments('id');
            $table->date('for_month');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
