<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameConstructionColumnToSrtInputDocumentsTable extends Migration
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
            $table->renameColumn('constuction_contract_id', 'construction_contract_id');
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
