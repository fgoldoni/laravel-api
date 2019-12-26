<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('dob')->nullable();
            $table->enum('gender', ['male', 'female '])->nullable();
            $table->string('country')->default('Germany');
            $table->string('company')->nullable();
            $table->string('department')->nullable();
            $table->string('mobile')->nullable();
            $table->string('website')->nullable();
            $table->enum('languages_known', ["English", "French", "Germany", "Arabic"])->nullable();
            $table->enum('contact_options', ["email", "message", "phone"])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);

            $table->rememberToken();
            $table->softDeletes();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
