<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextToSrtHeadOfficeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('srt_head_office_documents', function (Blueprint $table) {
            //
            $table->string('to_text')->nullable();

        });
    }

}
