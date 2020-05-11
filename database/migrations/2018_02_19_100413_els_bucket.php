<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ElsBucket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
         Schema::table('zones', function (Blueprint $table) {
            
            
            $table->string('els_bucket')->after('els_ts')->default('10');

           
           
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
            $table->dropColumn('els_bucket');
           
           
        });
    }
}
