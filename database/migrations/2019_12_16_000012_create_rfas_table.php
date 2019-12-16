<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfasTable extends Migration
{
    public function up()
    {
        Schema::create('rfas', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->nullable();

            $table->string('document_number')->nullable();

            $table->string('rfa_code')->nullable();

            $table->date('submit_date')->nullable();

            $table->longText('note_1')->nullable();

            $table->date('receive_date')->nullable();

            $table->longText('note_2')->nullable();

            $table->longText('note_3')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
