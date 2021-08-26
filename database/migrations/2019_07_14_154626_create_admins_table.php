<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('region')->nullable();
            $table->text("introduction")->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable($value = true);
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
