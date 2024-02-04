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
    Schema::create('phone_number_verifications', function (Blueprint $table) {
        $table->id();
        $table->string('country_code');
        $table->string('phone_number');
        $table->string('verification_code');
        $table->boolean('is_verified')->default(false);
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
        Schema::dropIfExists('phone_number_verifications');
    }
};
