<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistributeByToRfasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfas', function (Blueprint $table) {
            $table->unsignedInteger('distribute_by_id')->nullable();
            $table->foreign('distribute_by_id', 'distribute_by_fk_1005550')->references('id')->on('users');
        });
    }
}
