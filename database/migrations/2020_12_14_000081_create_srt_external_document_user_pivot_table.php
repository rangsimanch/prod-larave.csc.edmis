<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtExternalDocumentUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('srt_external_document_user', function (Blueprint $table) {
            $table->unsignedInteger('srt_external_document_id');
            $table->foreign('srt_external_document_id', 'srt_external_document_id_fk_2780983')->references('id')->on('srt_external_documents')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_9780983')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
