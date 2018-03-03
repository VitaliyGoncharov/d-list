<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrdRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frd_requests', function (Blueprint $table) {
            $table->unsignedInteger('from');
            $table->unsignedInteger('to');
            $table->dateTime('created_at');
            $table->primary(['from','to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frd_requests');
    }
}
