<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtInputDocumentUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('srt_input_document_user', function (Blueprint $table) {
            $table->unsignedInteger('srt_input_document_id');
            $table->foreign('srt_input_document_id', 'srt_input_document_id_fk_2240467')->references('id')->on('srt_input_documents')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_2240467')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
