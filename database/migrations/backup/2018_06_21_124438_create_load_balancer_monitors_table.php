<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadBalancerMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_balancer_monitors', function (Blueprint $table) {
             $table->increments('id');

            $table->string('record_id')->comment("Resource ID on Cloudflare");
            $table->string('type');
            $table->string('method');
            $table->string('description')->nullable();
           
            $table->string('path');
            $table->string('header');
            $table->string('timeout');
            $table->string('retries');

            $table->string('interval');
            $table->string('expected_body');
            $table->string('expected_codes');
           

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
        Schema::dropIfExists('load_balancer_monitors');
    }
}
