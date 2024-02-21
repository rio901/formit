<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('users')->insert([
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'password' => bcrypt('guestpassword'),
        ]);
    }

    public function down()
    {
        // ロールバックの処理を記述する場合はこちらに記述します。
    }
};
