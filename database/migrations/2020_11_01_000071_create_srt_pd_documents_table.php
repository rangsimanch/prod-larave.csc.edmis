<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSrtPdDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('srt_pd_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->date('process_date')->nullable();
            $table->string('special_command')->nullable();
            $table->date('finished_date')->nullable();
            $table->longText('practice_notes')->nullable();
            $table->string('note')->nullable();
            $table->string('save_for');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
