<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRfaCountAndAttachNameToRfasTable extends Migration
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
            $table->string('attach_file_name')->nullable();

            $table->string('rfa_count')->nullable();
        });
    }
}
