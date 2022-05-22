<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtExternalAgencyDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('srt_external_agency_documents', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('subject')->nullable();
            $table->string('document_type')->nullable();
            $table->string('originator_number')->nullable();
            $table->string('document_number')->nullable();
            $table->date('incoming_date')->nullable();
            $table->string('refer_to')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('attachments')->nullable();
            $table->longText('description')->nullable();
            $table->string('speed_class')->nullable();
            $table->string('objective')->nullable();
            $table->string('signatory')->nullable();
            $table->string('document_storage')->nullable();
            $table->longText('note')->nullable();
            $table->date('close_date')->nullable();
            $table->string('save_for');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
