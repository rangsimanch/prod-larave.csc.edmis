<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCCToAddLettersTable extends Migration
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
            $table->boolean('cc_srt')->default(0)->nullable();
            $table->boolean('cc_pmc')->default(0)->nullable();
            $table->boolean('cc_csc')->default(0)->nullable();
            $table->boolean('cc_cec')->default(0)->nullable();
        });
    }
}
