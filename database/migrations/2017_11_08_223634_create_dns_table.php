<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('record_id')->comment("Resource ID on Cloudflare");
            $table->string('type');
            $table->string('name');
            $table->text('content');
            $table->boolean('proxiable');
            $table->boolean('proxied');
            $table->integer('ttl')->default(1);
            $table->integer('priority')->default(0);

            $table->boolean('locked');
            $table->dateTimeTz('modified_on')->nullable();
            $table->integer('zone_id');
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
        Schema::dropIfExists('dns');
    }
}
