<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadBalancerPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_balancer_pools', function (Blueprint $table) {
            $table->increments('id');

              $table->string('record_id')->comment("Resource ID on Cloudflare");
            $table->string('name');
            $table->string('origins');
            $table->string('description')->nullable();
           
            $table->string('enabled');
            $table->string('minimum_origins');
            $table->string('monitor');
            $table->string('notification_email');

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
        Schema::dropIfExists('load_balancer_pools');
    }
}
