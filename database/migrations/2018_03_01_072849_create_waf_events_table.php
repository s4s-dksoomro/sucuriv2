<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWafEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waf_events', function (Blueprint $table) {
            $table->increments('id');
         
            $table->string('resource_id')->nullable();

            $table->string('count');
            $table->string('client_ip');
           
            $table->integer('timestamp');
            $table->string('ref_id');
            $table->string('scope_value')->nullable();
            $table->string('scope')->nullable();
            $table->string('result')->nullable();
            $table->string('country')->nullable();
            $table->string('action')->nullable();
            $table->string('method')->nullable();
             $table->string('type')->nullable();
            $table->string('rule_id')->nullable();
            $table->string('rule_name')->nullable();
            $table->string('blocked')->nullable();
            $table->string('scope_description')->nullable();
            $table->string('action_description')->nullable();
            $table->string('engine_description')->nullable();
            $table->string('engine')->nullable();
            $table->string('template')->nullable();
            $table->string('request_type')->nullable();
            $table->string('organization')->nullable();
            $table->string('scheme')->nullable();
            $table->string('domain')->nullable();
            $table->text('uri')->nullable();
            $table->text('query_string')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('headers')->nullable();
            $table->integer('zone_id')->default(1);
            $table->timestamps();
        });

         Schema::table('zones', function (Blueprint $table) {
            
            
            $table->string('waf_ts')->after('internalID')->default('');

           
           
        });

         Schema::table('brandings', function (Blueprint $table) {
            
            
            $table->string('cf')->after('user_id')->default('0');
            $table->string('sp')->after('cf')->default('0');

           
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waf_events');

         Schema::table('zones', function (Blueprint $table) {
          
             $table->dropColumn('waf_ts');
            
           
           
        });

         Schema::table('brandings', function (Blueprint $table) {
          
             $table->dropColumn('cf');
             $table->dropColumn('sp');
            
           
           
        });
    }
}
