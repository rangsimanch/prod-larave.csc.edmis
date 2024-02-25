<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('design_reviews', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('sheet_title');
            $table->string('url');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
