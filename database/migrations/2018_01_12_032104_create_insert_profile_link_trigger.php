<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsertProfileLinkTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER ins_profile_link AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                SET @user_id = NEW.id;
                SET @email = NEW.email;

                CALL ins_profile_link(@user_id, @email);
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
        DB::unprepared('DROP TRIGGER IF EXISTS ins_profile_link');
    }
}
