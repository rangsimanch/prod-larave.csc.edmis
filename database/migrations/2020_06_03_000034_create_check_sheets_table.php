<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckSheetsTable extends Migration
{
    public function up()
    {
        Schema::create('check_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_title')->nullable();
            $table->string('location')->nullable();
            $table->date('date_of_work_done')->nullable();
            $table->string('thai_or_chinese_work')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
