<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewedToRfasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfas', function (Blueprint $table) {
            //
             $table->unsignedInteger('reviewed_by_id')->nullable();
            $table->foreign('reviewed_by_id', 'reviewed_by_fk_1047177')->references('id')->on('users');
        });
    }
}
