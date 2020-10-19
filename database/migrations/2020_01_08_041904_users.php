<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //UsersTable migration 
        Schema::create('users',function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->uuid('uuid');             
        $table->string('name');             
        $table->string('email')->unique();             
        $table->string('password');
        $table->enum('type', ['admin', 'CSM', 'CSE']);
        $table->string("api_token")->nullable();             
        $table->timestamps();
        $table->softDeletes()->nullable();         
    });

}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
