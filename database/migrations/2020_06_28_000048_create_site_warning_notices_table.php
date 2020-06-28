<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteWarningNoticesTable extends Migration
{
    public function up()
    {
        Schema::create('site_warning_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->nullable();
            $table->string('location')->nullable();
            $table->string('reply_by_ncr')->nullable();
            $table->string('swn_no')->nullable();
            $table->date('submit_date')->nullable();
            $table->longText('description')->nullable();
            $table->longText('root_cause')->nullable();
            $table->longText('containment_actions')->nullable();
            $table->date('containment_completion_date')->nullable();
            $table->longText('corrective_and_preventive')->nullable();
            $table->date('corrective_completion_date')->nullable();
            $table->string('review_and_judgement_status')->nullable();
            $table->longText('note')->nullable();
            $table->string('disposition_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
