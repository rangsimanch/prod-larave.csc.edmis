<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfaAuthorizedSignatoryTable extends Migration
{
    public function up()
    {
        Schema::create('rfa_authorized_signatory', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('rfa_id');
            $table->foreign('rfa_id', 'rfa_id_fk_ras')->references('id')->on('rfas')->onDelete('cascade');
            $table->unsignedInteger('authorized_signatory_id');
            $table->foreign('authorized_signatory_id', 'signatory_id_fk_ras')->references('id')->on('authorized_signatories')->onDelete('cascade');
            $table->unsignedInteger('authorized_signatory_jobtitle_id')->nullable();
            $table->foreign('authorized_signatory_jobtitle_id', 'signatory_jobtitle_id_fk_ras')->references('id')->on('authorized_signatory_jobtitles')->onDelete('set null');
            $table->unsignedTinyInteger('slot')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rfa_authorized_signatory');
    }
}
