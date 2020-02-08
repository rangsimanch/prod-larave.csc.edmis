<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittalsRfasTable extends Migration
{
    public function up()
    {
        Schema::create('submittals_rfas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_no')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty_sets')->nullable();
            $table->date('date_returned')->nullable();
            $table->longText('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
