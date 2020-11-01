<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtPdDocumentUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('srt_pd_document_user', function (Blueprint $table) {
            $table->unsignedInteger('srt_pd_document_id');
            $table->foreign('srt_pd_document_id', 'srt_pd_document_id_fk_2504448')->references('id')->on('srt_pd_documents')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_2504448')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
