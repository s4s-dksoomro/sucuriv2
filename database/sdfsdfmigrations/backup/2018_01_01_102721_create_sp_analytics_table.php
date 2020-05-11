<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('period')->default('hourly');
            $table->string('size')->nullable();
            $table->string('hit')->nullable();
            $table->string('noncache_hit')->nullable();
            $table->string('cache_hit')->nullable();
            $table->string('bots')->nullable();
            $table->string('blocked')->nullable();
            $table->string('custom')->nullable();
            $table->string('status_code')->nullable();
             $table->string('definition')->nullable();
            $table->string('file_type')->nullable();
            $table->dateTime('timestamp')->nullable();

            $table->integer('zone_id');
            $table->string('type')->default('stats');
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
        Schema::dropIfExists('sp_analytics');
    }
}
