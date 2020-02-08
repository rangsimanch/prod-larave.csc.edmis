<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSubmittalsRfasTable extends Migration
{
    public function up()
    {
        Schema::table('submittals_rfas', function (Blueprint $table) {
            $table->unsignedInteger('review_status_id')->nullable();
            $table->foreign('review_status_id', 'review_status_fk_971749')->references('id')->on('rfa_comment_statuses');
            $table->unsignedInteger('on_rfa_id')->nullable();
            $table->foreign('on_rfa_id', 'on_rfa_fk_971752')->references('id')->on('rfas');
        });
    }
}
