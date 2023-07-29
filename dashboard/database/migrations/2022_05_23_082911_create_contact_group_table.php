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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image');
            $table->string('number')->unique();
            $table->text('info1');
            $table->text('info2');
            $table->text('info3');
            $table->integer('id_user');
            $table->timestamps();
        });

        Schema::create('blast', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('text')->nullable();
            $table->text('footer')->nullable();
            $table->text('buttons')->nullable();
            $table->string('status', 15)->nullable();
            $table->integer('id_type');
            $table->integer('id_user');
            $table->string('link')->nullable();
            $table->timestamps();
        });
        
        Schema::create('blastcontact', function (Blueprint $table) {
            $table->id();
            $table->integer('id_contact');
            $table->integer('id_blast');
            $table->integer('id_device')->nullable();
            $table->boolean('status');
            $table->timestamps();

            $table->index(['id_contact', 'id_blast']);
        });

        Schema::create('blastdevice', function (Blueprint $table) {
            $table->id();
            $table->integer('id_device');
            $table->integer('id_blast');
            $table->timestamps();
            
            $table->index(['id_device', 'id_blast']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact');
        Schema::dropIfExists('blast');
        Schema::dropIfExists('blastcontact');
        Schema::dropIfExists('blastdevice');
    }
};
