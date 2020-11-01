<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFKToSrtInputDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('srt_input_documents', function (Blueprint $table) {
            //
            $table->unsignedInteger('from_id')->nullable();
            $table->foreign('from_id', 'from_fk_2240466')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('srt_input_documents', function (Blueprint $table) {
            //
        });
    }
}
