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
        Schema::table('users', function (Blueprint $table) {
             // Add is_borrowed field
             $table->boolean('is_borrowed')->default(false);

             // Add borrowed_count field
             $table->integer('borrowed_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
               // Reverse the changes if needed
                $table->dropColumn('is_borrowed');
               $table->dropColumn('borrowed_count');
        });
    }
};
