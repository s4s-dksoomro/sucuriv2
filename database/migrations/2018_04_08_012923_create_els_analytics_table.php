<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElsAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('els_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('minutes')->default(1440)->comment('Data for Last N minutes');
            $table->longText('value')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('els_analytics');
    }
}
