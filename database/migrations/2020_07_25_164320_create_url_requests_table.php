<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('url_id');
//            $table->unsignedBigInteger('user_id');

            $table->enum('status', ['pending', 'completed'])->default('pending');

            $table->timestamps();

//            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('url_id')->references('id')->on('urls');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_requests');
    }
}
