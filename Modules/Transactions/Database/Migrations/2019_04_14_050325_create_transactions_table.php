<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gateway')->nullable();
            $table->string('transaction_key')->nullable();
            $table->string('transaction_balance')->nullable();
            $table->string('status')->nullable();
            $table->double('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('last4')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('created')->nullable();
            $table->longText('metadata');
            $table->longText('detail');
            $table->string('token')->nullable();
            $table->string('domain')->nullable();
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->integer('provider_id')->unsigned()->index()->nullable();
            $table->integer('parent_id')->unsigned()->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('customer_id', 'customer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_id', 'provider')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('transactions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
