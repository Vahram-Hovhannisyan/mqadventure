<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->jsonb('value'); // always JSON, even for plain strings: {"hy":"...", "ru":"...", "en":"..."} or {"value": "..."}
            $table->string('type')->default('text'); // text | textarea | image | boolean
            $table->string('group')->default('general'); // general | hero | about | contacts
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
