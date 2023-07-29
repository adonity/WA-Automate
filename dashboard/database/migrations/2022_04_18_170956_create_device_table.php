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
        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->integer('id_users');
            $table->string('number', 20)->unique();
            $table->string('name')->unique();
            $table->string('description')->nullable();
			$table->boolean('multidevice');
            $table->string('status', 15);
            $table->string('key', 60);
            $table->text('qrcode')->nullable();
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
        Schema::dropIfExists('device');
    }
};
