<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTitleAndReviewToRfasTable extends Migration
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
            
            $table->string('title_eng')->nullable();

            $table->string('title_cn')->nullable();

            $table->integer('review_time')->nullable();
        });
    }
}
