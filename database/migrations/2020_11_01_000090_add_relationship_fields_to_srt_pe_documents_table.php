<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSrtPeDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('srt_pe_documents', function (Blueprint $table) {
            $table->unsignedInteger('refer_documents_id');
            $table->foreign('refer_documents_id', 'refer_documents_fk_2504458')->references('id')->on('srt_input_documents');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_2504470')->references('id')->on('teams');
        });
    }
}
