<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStylistReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stylist_reviews', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('stylist_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('menu_id')->default(0);
            $table->date('date');
            $table->time('startTime');
            $table->integer('stars')->default(0);
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('stylist_id')->references('id')->on('stylists')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stylist_reviews');
    }
}
