<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('document_records', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('title');
            $table->string('category');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
