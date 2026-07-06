<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blocked_slots', function (Blueprint $table) {
            $table->id();

            // Null tour_id = applies to ALL tours (e.g. a public holiday, company closed)
            $table->foreignId('tour_id')->nullable()->constrained()->cascadeOnDelete();

            $table->date('date');

            // Both null = the entire day is blocked.
            // Otherwise this specific time range is blocked.
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['date', 'tour_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_slots');
    }
};
