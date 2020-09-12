<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtHeadOfficeDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('srt_head_office_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->date('process_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->string('practitioner')->nullable();
            $table->longText('practice_notes')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
