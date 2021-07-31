<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStylistCutAndColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stylist_cut_and_colors', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('admin_id');
            $table->unsignedInteger('stylist_id');
            $table->date('date');
            $table->time('startTime');
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
        Schema::dropIfExists('stylist_cut_and_colors');
    }
}
