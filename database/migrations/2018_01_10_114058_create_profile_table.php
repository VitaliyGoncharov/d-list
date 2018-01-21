<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('email',30);
            $table->string('city',30)->nullable();
            $table->string('school',30)->nullable();
            $table->string('university',30)->nullable();
            $table->string('job',30)->nullable();
            $table->integer('cellphone')->nullable();
            $table->integer('friends')->nullable();
            $table->integer('followers')->nullable();
            $table->integer('photos')->nullable();
            $table->integer('tags')->nullable();
            $table->integer('videos')->nullable();
            $table->integer('audiofiles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile');
    }
}
