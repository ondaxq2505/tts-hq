<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('shop_id')->nullable();
            $table->string('username')->index();
            $table->text('fullname')->nullable();
            $table->tinyInteger('account_type')->default(1);
            $table->string('email')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->text('password')->nullable();
            $table->text('password2')->nullable();
            $table->decimal('balance', 10, 2)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('required_login_gmail')->nullable();
            $table->string('provider_id')->nullable();
            $table->tinyInteger('google2fa_enable')->nullable();
            $table->text('google2fa_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();
            $table->timestamps(); // created_at, updated_at
            $table->string('avatar')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
