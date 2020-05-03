<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->double('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->integer('provider_id')->unsigned()->index()->nullable();
            $table->integer('transaction_id')->unsigned()->index()->nullable();
            $table->integer('ticket_id')->unsigned()->index()->nullable();
            $table->integer('event_id')->unsigned()->index()->nullable();

            $table->foreign('customer_id', 'customer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_id', 'provider')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

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
        Schema::dropIfExists('orders');
    }
}
