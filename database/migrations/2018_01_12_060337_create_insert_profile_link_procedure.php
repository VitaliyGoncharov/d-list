<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsertProfileLinkProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE ins_profile_link (IN user_id INT, IN email VARCHAR(30))
            BEGIN
                INSERT INTO `profile_link` (`user_id`,`link`) VALUES (user_id, CONCAT(\'id\', user_id));
                INSERT INTO `profile` (`user_id`,`email`) VALUES (user_id, email);
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS ins_profile_link');
    }
}
