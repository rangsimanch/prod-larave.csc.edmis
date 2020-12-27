<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddLettersTable extends Migration
{
    public function up()
    {
        Schema::create('add_letters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('letter_no');
            $table->date('sent_date');
            $table->date('received_date')->nullable();
            $table->string('letter_type');
            $table->string('letter_iso_no')->nullable();
            $table->string('speed_class');
            $table->string('objective');
            $table->boolean('mask_as_received')->default(0)->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
