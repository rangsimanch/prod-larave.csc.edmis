<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAddLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_letters', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('complete_date')->nullable();
            $table->integer('processing_time')->nullable();
            $table->unsignedInteger('responsible_id')->nullable();
            $table->foreign('responsible_id', 'responsible_fk_6605504')->references('id')->on('users');
        });
    }
}
