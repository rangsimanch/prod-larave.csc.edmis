<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToDocumentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_records', function (Blueprint $table) {
            //
            $table->string('document_number')->nullable();

            $table->string('translated_by')->nullable();
        });
    }
}
