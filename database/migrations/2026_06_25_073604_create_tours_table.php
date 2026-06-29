<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('badge_color')->default('orange'); // orange | green
            $table->integer('duration_min');   // hours min
            $table->integer('duration_max');   // hours max
            $table->integer('people_min');
            $table->integer('people_max');
            $table->integer('price_from');     // AMD
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('image')->nullable();
            // Translatable JSON fields
            $table->jsonb('name');       // {"hy":"...", "ru":"...", "en":"..."}
            $table->jsonb('badge');      // {"hy":"...", "ru":"...", "en":"..."}
            $table->jsonb('description'); // multiline
            $table->jsonb('route_points')->nullable()->after('image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
