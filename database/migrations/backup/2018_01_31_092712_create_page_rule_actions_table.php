<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\user;

class CreatePageRuleActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_rule_actions', function (Blueprint $table) {
             $table->increments('id');
             $table->integer("pagerule_id");
             $table->string("action");
            $table->string("value")->nullable();
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
        Schema::dropIfExists('page_rule_actions');
    }
}
