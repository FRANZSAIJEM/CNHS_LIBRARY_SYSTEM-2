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
        Schema::create('default_fines', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Describe the type of fine, e.g., "Late return fine"
            $table->decimal('amount', 10, 2); // The default fine amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_fines');
    }
};
