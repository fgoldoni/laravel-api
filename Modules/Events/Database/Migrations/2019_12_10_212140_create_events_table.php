<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('fee')->default('0');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_name')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->string('url')->nullable();
            $table->string('color')->default('#00695C');
            $table->boolean('all_day')->default(true);
            $table->boolean('online')->default(false);
            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->morphs('eventable');
            $table->enum('theme', ['standard', 'profile'])->default('standard');
            $table->string('video')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('event_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('event_tag', function (Blueprint $table) {
            $table->integer('tag_id')->nullable()->unsigned()->index();
            $table->integer('event_id')->nullable()->unsigned()->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        if (Schema::hasTable('transactions') && !Schema::hasColumn('transactions', 'event_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_tag');
        Schema::dropIfExists('event_user');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
