<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTagsTable extends Migration
{
    public function up()
    {
        Schema::create('document_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
