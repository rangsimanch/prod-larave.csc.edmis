<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtOtherUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('srt_other_user', function (Blueprint $table) {
            $table->unsignedInteger('srt_other_id');
            $table->foreign('srt_other_id', 'srt_other_id_fk_4695268')->references('id')->on('srt_others')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_4695268')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
