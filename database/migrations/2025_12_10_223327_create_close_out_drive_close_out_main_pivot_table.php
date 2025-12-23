<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutDriveCloseOutMainPivotTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_drive_close_out_main', function (Blueprint $table) {
            $table->unsignedInteger('close_out_main_id');
            $table->foreign('close_out_main_id', 'close_out_main_id_fk_10776282')->references('id')->on('close_out_mains')->onDelete('cascade');
            $table->unsignedInteger('close_out_drive_id');
            $table->foreign('close_out_drive_id', 'close_out_drive_id_fk_10776282')->references('id')->on('close_out_drives')->onDelete('cascade');
        });
    }
}
