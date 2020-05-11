<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('zone_id');
            $table->string('resource_id');
            $table->text('hosts');
            $table->text('issuer');
            $table->string('signature');
            $table->string('status');
            $table->string('bundle_method');
            $table->integer('priority');
            $table->string('expires_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_certificates');
    }
}
