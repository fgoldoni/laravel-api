<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToRoles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'deleted_at')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if ('testing' !== app()->environment()) {
            if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'deleted_at')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->dropColumn('deleted_at');
                });
            }
        }
    }
}
