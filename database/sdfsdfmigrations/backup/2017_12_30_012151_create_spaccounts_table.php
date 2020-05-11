<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias')->unique();
            $table->string('key')->default("");
            $table->string('secret')->default("");
           
            $table->integer('zones')->default(0);
            $table->integer('reseller_id')->default(1);
            $table->integer('type')->default(0);
            $table->timestamps();
        });



        Schema::table('zones', function (Blueprint $table) {
            $table->integer('spaccount_id')->default(1);
            $table->integer('hosted_on')->default(0)->comment("0=Cloudflare, 1=StackPath");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spaccounts');

        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn('spaccount_id');
            $table->dropColumn('hosted_on');
        });

    }
}
