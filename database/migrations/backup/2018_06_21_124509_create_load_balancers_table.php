<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadBalancersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_balancers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('record_id')->comment("Resource ID on Cloudflare");
            $table->string('name');
            $table->string('fallback_pool');
            $table->string('default_pools');
            $table->string('description')->nullable();
           
            $table->string('ttl');
            $table->string('region_pools');
            $table->string('pop_pools');
            $table->string('proxied');

         
           

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
        Schema::dropIfExists('load_balancers');
    }
}
