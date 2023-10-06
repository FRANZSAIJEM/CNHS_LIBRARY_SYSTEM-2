<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_duration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accepted_request_id'); // Foreign key to link to accepted_request
            $table->integer('date_pickup_seconds');
            $table->integer('date_return_seconds');
            $table->timestamps();

            $table->foreign('accepted_request_id')->references('id')->on('accepted_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_duration');
    }
};
