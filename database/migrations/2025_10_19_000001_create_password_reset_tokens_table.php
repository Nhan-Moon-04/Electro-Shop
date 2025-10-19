<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('MaNguoiDung');
            $table->string('Token', 100);
            $table->dateTime('ExpireAt');
            $table->boolean('Used')->default(false);

            // Note: do NOT add a foreign key constraint here because the existing
            // `users` table in this project dump doesn't declare `user_id` as a
            // primary/unique key which would cause FK creation to fail. We keep
            // MaNguoiDung as an indexed column for lookups.
            $table->index('MaNguoiDung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
