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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key to associate payments with users
            $table->string('payment_id'); // Stripe payment ID or other payment gateway's ID
            $table->decimal('amount', 10, 2); // Payment amount in decimal format
            $table->string('currency', 3); // Currency code, e.g., USD, EUR
            $table->string('status'); // Payment status, e.g., succeeded, pending
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }

};
