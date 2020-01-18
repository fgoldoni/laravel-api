<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('offer_1')->nullable();
            $table->string('offer_2')->nullable();
            $table->string('offer_3')->nullable();
            $table->string('offer_4')->nullable();
            $table->double('price', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->boolean('free')->default(false);
            $table->boolean('online')->default(false);
            $table->integer('position')->default(0);

            $table->integer('user_id')->unsigned()->index();
            $table->integer('event_id')->unsigned()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('event_ticket');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
