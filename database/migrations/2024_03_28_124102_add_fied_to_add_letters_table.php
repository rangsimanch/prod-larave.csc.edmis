<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiedToAddLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_letters', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->string('repiled_ref')->nullable();
        });
    }
}
