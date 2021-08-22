<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtOthersTable extends Migration
{
    public function up()
    {
        Schema::create('srt_others', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();
            $table->string('subject')->nullable();
            $table->date('incoming_date')->nullable();
            $table->string('refer_to')->nullable();
            $table->string('attachments')->nullable();
            $table->longText('description')->nullable();
            $table->string('speed_class')->nullable();
            $table->string('objective')->nullable();
            $table->string('signatory')->nullable();
            $table->string('document_storage')->nullable();
            $table->longText('note')->nullable();
            $table->date('close_date')->nullable();
            $table->string('save_for');
            $table->string('to_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
