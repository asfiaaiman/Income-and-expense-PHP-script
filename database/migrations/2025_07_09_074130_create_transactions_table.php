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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2);
            $table->decimal('commission', 15, 2)->default(0.00);

            // Foreign key relationships
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Delete transactions when user is deleted

            $table->timestamps();

            // Performance indexes
            $table->index(['user_id', 'type']); // For filtering user transactions by type
            $table->index(['created_at']);       // For date range queries
            $table->index(['amount']);           // For amount-based queries
            $table->index(['user_id', 'created_at']); // For user transaction history
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
