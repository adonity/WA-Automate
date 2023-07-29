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
        Schema::create('outbox', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->text('text')->nullable();
            $table->text('footer')->nullable();
            $table->text('buttons')->nullable();
            $table->string('status', 15)->nullable();
            $table->integer('id_device');
            $table->integer('id_type');
            $table->integer('id_user');
            $table->string('link')->nullable();
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
        Schema::dropIfExists('outbox');
    }
};
