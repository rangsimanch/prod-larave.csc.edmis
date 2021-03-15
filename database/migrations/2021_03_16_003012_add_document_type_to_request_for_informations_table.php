<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentTypeToRequestForInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_for_informations', function (Blueprint $table) {
            $table->unsignedInteger('document_type_id')->nullable();
            $table->foreign('document_type_id', 'document_type_fk_3444225')->references('id')->on('rfatypes');
        });
    }
}
