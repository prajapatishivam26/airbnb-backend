<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('surname');
            $table->date('d_o_b');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->unique();
            $table->string('otp')->nullable(); // OTP for phone verification
            $table->text('address')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->string('login_type'); // E.g., 'email' or 'phone'
            $table->string('login_id')->unique(); // Email or phone number
            $table->string('token')->nullable(); // JWT token for authentication
            $table->string('emergency_contact')->nullable();
            $table->string('government_id')->nullable();
            $table->string('remeber_token');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}