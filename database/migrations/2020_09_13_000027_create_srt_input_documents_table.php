<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtInputDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('srt_input_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_type')->nullable();
            $table->string('document_number');
            $table->date('incoming_date');
            $table->string('refer_to')->nullable();
            $table->string('attachments')->nullable();
            $table->longText('description')->nullable();
            $table->string('speed_class')->nullable();
            $table->string('objective')->nullable();
            $table->string('document_storage')->nullable();
            $table->longText('note')->nullable();
            $table->string('registration_number')->unique();
            $table->string('signatory')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
