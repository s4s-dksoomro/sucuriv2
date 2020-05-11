<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('zone_id');
            $table->integer('user_id')->default(1);
            $table->integer('cfaccount_id')->default(1);
            $table->integer('package_id')->default(1);
            $table->string('name_server1')->default("");
            $table->string('name_server2')->default("");
            $table->string('plan')->default("free");
            $table->string('status')->default("active");
            $table->boolean('paused')->default(false);
            $table->string('type')->default("full");
            
            

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
        Schema::dropIfExists('zones');
    }
}
