<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWafRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waf_rules', function (Blueprint $table) {
            
             $table->increments('id');
            
            $table->text("description");
            $table->string("priority");
            $table->text("mode");
            $table->text("default_mode")->nullable();
            $table->integer('group_id');
            $table->text('allowed_modes');


            $table->string('record_id')->comment("Resource ID on Cloudflare");
            
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
        Schema::dropIfExists('waf_rules');
    }
}
