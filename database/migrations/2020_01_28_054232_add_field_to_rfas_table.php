<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToRfasTable extends Migration
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
            $table->unsignedInteger('actionby_id')->nullable();

            $table->foreign('actionby_id', 'actionby_fk_594337')->references('id')->on('users');

            $table->string('incoming_number')->nullable();

            $table->string('outgoing_number')->nullable();

            $table->date('distribute_date')->nullable();

            $table->date('process_date')->nullable();

            $table->date('outgoing_date')->nullable();

            $table->longText('note_4')->nullable();

            $table->unsignedInteger('action_by_id')->nullable();

            $table->foreign('action_by_id', 'action_by_fk_930221')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfas', function (Blueprint $table) {
            //
        });
    }
}
