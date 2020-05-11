<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Softdelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('zones', function ($table) {
            $table->softDeletes();
        });

         Schema::create('custom_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('custom_domain');
            $table->string('zone_id');
            $table->string('resource_id');
            $table->string('type')->nullable();

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
        //
         Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
           
        });
         Schema::dropIfExists('custom_domains');
    }
}
