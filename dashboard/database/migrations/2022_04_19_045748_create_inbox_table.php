<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->text('text')->nullable();
            $table->text('link')->nullable();
            $table->integer('id_device')->nullable();
            $table->boolean('me')->default(false);
            $table->string('push_name')->nullable();
            $table->integer('read')->default(0);
            $table->string('id_wa')->nullable();
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
        Schema::dropIfExists('inbox');
    }
};
