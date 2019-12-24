<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractsTable extends Migration
{
    public function up()
    {
        Schema::create('construction_contracts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->string('code');

            $table->string('dk_start_1')->nullable();

            $table->string('dk_end_1')->nullable();

            $table->string('dk_start_2')->nullable();

            $table->string('dk_end_2')->nullable();

            $table->string('dk_start_3')->nullable();

            $table->string('dk_end_3')->nullable();

            $table->float('roadway_km', 15, 2)->nullable();

            $table->float('tollway_km', 15, 2)->nullable();

            $table->string('total_distance_km')->nullable();

            $table->decimal('budget', 15, 2)->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
