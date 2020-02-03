<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManyFieldToRfasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfas', function (Blueprint $table) {
            //
            $table->string('origin_number')->nullable();

            $table->string('document_ref')->nullable();

            $table->longText('document_description')->nullable();

            $table->date('target_date')->nullable();

            $table->date('hardcopy_date')->nullable();

        });
    }
}
