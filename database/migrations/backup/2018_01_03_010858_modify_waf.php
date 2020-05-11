<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWaf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

         Schema::table('waf_groups', function (Blueprint $table) {
            $table->string('action')->after('mode')->default("");
            $table->string('rules_count')->default("")->change();
           
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
         Schema::table('waf_groups', function (Blueprint $table) {
            $table->dropColumn('action');
           
        });

    }
}
