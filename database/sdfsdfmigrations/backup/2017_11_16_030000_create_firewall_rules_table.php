<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirewallRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firewall_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('record_id')->comment("Resource ID on Cloudflare");
            $table->string('mode');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->string('scope');
            $table->string('value');
            $table->string('target')->default('ip');
            

            $table->integer('zone_id');
            $table->dateTimeTz('modified_on')->nullable();

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
        Schema::dropIfExists('firewall_rules');
    }
}
