<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtHeadOfficeDocumentUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('srt_head_office_document_user', function (Blueprint $table) {
            $table->unsignedInteger('srt_head_office_document_id');
            $table->foreign('srt_head_office_document_id', 'srt_head_office_document_id_fk_2240442')->references('id')->on('srt_head_office_documents')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_2240442')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
