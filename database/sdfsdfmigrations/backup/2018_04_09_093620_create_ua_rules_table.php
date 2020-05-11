<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUaRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ua_rules', function (Blueprint $table) {
            $table->increments('id');

            $table->string('record_id')->comment("Resource ID on Cloudflare");
            $table->string('mode');
            $table->string('paused');
            $table->string('description')->nullable();
           
            $table->string('value');
            $table->string('target')->default('ua');
            

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
        Schema::dropIfExists('ua_rules');
    }
}
