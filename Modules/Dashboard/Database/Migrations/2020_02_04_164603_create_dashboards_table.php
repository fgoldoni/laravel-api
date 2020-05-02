<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDashboardsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('component');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('dashboard_user', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('x');
            $table->smallInteger('y');
            $table->smallInteger('w');
            $table->smallInteger('h');
            $table->integer('i');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('dashboard_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dashboard_id')->references('id')->on('dashboards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if ('testing' !== app()->environment()) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }

        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('dashboard_user');

        if ('testing' !== app()->environment()) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
