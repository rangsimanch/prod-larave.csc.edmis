<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoqItemRequestForInspectionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('boq_item_request_for_inspection', function (Blueprint $table) {
            $table->unsignedInteger('request_for_inspection_id');
            $table->foreign('request_for_inspection_id', 'request_for_inspection_id_fk_1869795')->references('id')->on('request_for_inspections')->onDelete('cascade');
            $table->unsignedInteger('boq_item_id');
            $table->foreign('boq_item_id', 'boq_item_id_fk_1869795')->references('id')->on('boq_items')->onDelete('cascade');
        });
    }
}
