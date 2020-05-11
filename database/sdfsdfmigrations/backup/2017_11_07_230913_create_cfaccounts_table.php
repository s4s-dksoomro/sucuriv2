<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCfaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cfaccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('user_key')->default("");
            $table->string('unique_id')->default("");
            $table->string('user_api_key');
            $table->integer('zones')->default(0);
            $table->integer('reseller_id')->default(1);
            $table->integer('type')->default(0);
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
        Schema::dropIfExists('cfaccounts');
    }
}
