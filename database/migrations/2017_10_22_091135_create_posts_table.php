<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('photos',1000)->nullable();
            $table->string('files',1000)->nullable();
            $table->unsignedInteger('likes')->nullable();
            $table->unsignedInteger('dislikes')->nullable();
            $table->dateTime('created_at');
        });

        DB::statement('ALTER TABLE posts ADD FULLTEXT posts_fulltext_index(text)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX posts_fulltext_index on posts');
        Schema::dropIfExists('posts');
    }
}
