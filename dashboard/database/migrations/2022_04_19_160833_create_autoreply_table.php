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
        Schema::create('autoreply', function (Blueprint $table) {
            $table->id();
            $table->integer('id_device')->nullable();
            $table->string('trigger');
            $table->text('text')->nullable();
            $table->text('footer')->nullable();
            $table->text('buttons')->nullable();
            $table->integer('id_type');
            $table->integer('id_user');
            $table->string('link')->nullable();
            $table->string('status')->default(0);
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
        Schema::dropIfExists('autoreply');
    }
};
