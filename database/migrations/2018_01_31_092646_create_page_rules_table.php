<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_rules', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("zone_id");
           
            $table->string("priority")->nullable();
            $table->string("status");
            $table->text("value");
           


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
        Schema::dropIfExists('page_rules');
    }
}
