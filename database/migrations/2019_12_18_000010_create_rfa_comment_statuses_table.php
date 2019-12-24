<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfaCommentStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('rfa_comment_statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
