<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProcessingTimeToAddLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_letters', function (Blueprint $table) {
            //
            $table->string('processing_time')->change();

        });
    }
}
