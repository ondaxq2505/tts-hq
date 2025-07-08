<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment
            $table->integer('shop_id')->nullable();
            $table->string('title');
            $table->string('name');
            $table->integer('parent_id')->nullable();
            $table->integer('order')->nullable();
            $table->text('description')->nullable();
            $table->string('guard_name');
            $table->tinyInteger('type_information')->nullable();
            $table->text('shop_access')->nullable();
            $table->text('shop_expect')->nullable();
            $table->timestamps(); // created_at, updated_at
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
