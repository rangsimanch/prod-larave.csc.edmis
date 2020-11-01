<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFKToSrtInputDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('srt_input_documents', function (Blueprint $table) {
            // How to Drop FK on migration
                // $table->dropForeign('FK_Name')
                // $table->dropColumn('Column_Name');
                
            $table->dropForeign('from_fk_2240466');
            $table->dropColumn('from_id');
        });
    }
}
