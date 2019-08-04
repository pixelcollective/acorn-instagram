<?php

namespace TinyPixel\Acorn\Spectacle\Migrations;

class CreateInstagramTable extends Migration
{
    public function up()
    {
        Schema::create('instagram', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('access_token');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
