<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Els extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('zones', function (Blueprint $table) {
            
            $table->integer('els')->after('type')->default(0);
            $table->string('els_ts')->after('els')->default('');
            $table->string('internalID')->after('els_ts')->default('');

           
           
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
            $table->dropColumn('els');
            $table->dropColumn('els_ts');
             $table->dropColumn('internalID');
            
           
           
        });
    }
}
