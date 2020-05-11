<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTableWhmcs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        //

         Schema::table('users', function (Blueprint $table) {
            $table->string('whmcs_token')->after('remember_token')->default("")->nullable();
          
           
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
         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('whmcs_token');
           
        });

    }
}
