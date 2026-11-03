<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveSignatureFieldsToAuthorizedSignatoriesTable extends Migration
{
    public function up()
    {
        Schema::table('authorized_signatories', function (Blueprint $table) {
            $table->string('signature_path')->nullable()->after('name');
            $table->integer('signature_size_h')->nullable()->after('signature_path');
            $table->integer('signature_size_w')->nullable()->after('signature_size_h');
        });

        Schema::table('authorized_signatory_jobtitles', function (Blueprint $table) {
            $table->dropColumn(['signature_path', 'signature_size_h', 'signature_size_w']);
        });
    }

    public function down()
    {
        Schema::table('authorized_signatory_jobtitles', function (Blueprint $table) {
            $table->string('signature_path')->nullable();
            $table->integer('signature_size_h')->nullable();
            $table->integer('signature_size_w')->nullable();
        });

        Schema::table('authorized_signatories', function (Blueprint $table) {
            $table->dropColumn(['signature_path', 'signature_size_h', 'signature_size_w']);
        });
    }
}
