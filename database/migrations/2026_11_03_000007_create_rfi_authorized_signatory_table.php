<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfiAuthorizedSignatoryTable extends Migration
{
    public function up()
    {
        Schema::create('rfi_authorized_signatory', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('request_for_information_id');
            $table->foreign('request_for_information_id', 'rfi_id_fk_ras')->references('id')->on('request_for_informations')->onDelete('cascade');
            $table->unsignedInteger('authorized_signatory_id');
            $table->foreign('authorized_signatory_id', 'signatory_id_fk_rfias')->references('id')->on('authorized_signatories')->onDelete('cascade');
            $table->unsignedInteger('authorized_signatory_jobtitle_id')->nullable();
            $table->foreign('authorized_signatory_jobtitle_id', 'signatory_jobtitle_id_fk_rfias')->references('id')->on('authorized_signatory_jobtitles')->onDelete('set null');
            $table->unsignedTinyInteger('slot')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rfi_authorized_signatory');
    }
}
