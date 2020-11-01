<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtPeDocumentUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('srt_pe_document_user', function (Blueprint $table) {
            $table->unsignedInteger('srt_pe_document_id');
            $table->foreign('srt_pe_document_id', 'srt_pe_document_id_fk_2504462')->references('id')->on('srt_pe_documents')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_2504462')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
