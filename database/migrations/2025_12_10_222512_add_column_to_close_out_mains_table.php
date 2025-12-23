<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCloseOutMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('close_out_mains', function (Blueprint $table) {
            //
            $table->string('status');
            $table->string('detail');
            $table->longText('description')->nullable();
            $table->string('quantity')->nullable();
            $table->string('ref_documents')->nullable();
            $table->longText('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('close_out_mains', function (Blueprint $table) {
            //
        });
    }
}
