<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizedSignatoryJobtitlesTable extends Migration
{
    public function up()
    {
        Schema::create('authorized_signatory_jobtitles', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('jobtitle');
            $table->string('signature_path')->nullable();
            $table->integer('signature_size_h')->nullable();
            $table->integer('signature_size_w')->nullable();
            $table->integer('signature_position_top')->nullable();
            $table->integer('signature_position_left')->nullable();
            $table->integer('jobtitle_position_lf')->nullable();
            $table->integer('issue_position_lf')->nullable();
            $table->integer('issue_position_lf_sub')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('authorized_signatory_jobtitles');
    }
}
