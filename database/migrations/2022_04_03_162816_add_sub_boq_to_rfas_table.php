<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubBoqToRfasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfas', function (Blueprint $table) {
            $table->unsignedInteger('boq_sub_id')->nullable();
            $table->foreign('boq_sub_id', 'boq_sub_fk_6351059')->references('id')->on('bo_qs');
        });
    }
}
