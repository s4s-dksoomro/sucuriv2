<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_rules', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("zone_id");
            $table->text("description")->nullable();
            $table->string("action");
            $table->string("active");
            $table->text("name");
           


            $table->string('record_id')->comment("Resource ID on StackPath");

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
        Schema::dropIfExists('sp_rules');
    }
}
