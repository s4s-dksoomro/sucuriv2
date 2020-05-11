<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadBalancerLoadBalancerPoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_balancer_load_balancer_pool', function (Blueprint $table) {
            //
        $table->increments('id');
        $table->integer('load_balancer_id');
        $table->integer('load_balancer_pool_id');
        $table->string('relType');
        $table->string('location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('load_balancer_load_balancer_pool', function (Blueprint $table) {
            //
        });

        Schema::dropIfExists('load_balancer_load_balancer_pool');
    }
}
