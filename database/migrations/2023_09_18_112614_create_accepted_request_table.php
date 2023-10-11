<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('accepted_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('borrower_id');
            $table->string('book_title'); // Add the book title column
            $table->timestamp('date_borrow')->nullable();
            $table->timestamp('date_pickup')->nullable();
            $table->timestamp('date_return')->nullable();
            $table->decimal('fines', 10, 2)->nullable();
            $table->boolean('fines_applied')->default(false);
            $table->unsignedBigInteger('default_fine_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('book_id')->references('id')->on('books');
            $table->foreign('default_fine_id')->references('id')->on('default_fines'); // Foreign key to default_fines table
            // Add any other necessary foreign key constraints.

            // Additional columns or constraints can be added here.

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accepted_request');
    }
};
