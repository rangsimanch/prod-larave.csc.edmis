<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherFieldOnPanelAToRfasTable extends Migration
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
            
            $table->string('bill')->nullable();

            $table->string('qty_page')->nullable();

            $table->string('spec_ref_no')->nullable();

            $table->string('clause')->nullable();

            $table->string('contract_drawing_no')->nullable();
        });
    }
}
