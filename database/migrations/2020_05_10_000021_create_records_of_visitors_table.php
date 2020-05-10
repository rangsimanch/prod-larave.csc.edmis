<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsOfVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('records_of_visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_of_visit')->nullable();
            $table->string('name_of_visitor')->nullable();
            $table->longText('details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
