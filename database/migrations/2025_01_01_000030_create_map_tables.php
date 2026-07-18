<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 39th-IB infestation map data.
// Sources: frmap_barangays, color_history.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_barangays', function (Blueprint $table) {
            $table->id();                        // was id
            $table->string('fid', 50)->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->integer('frs')->default(0);          // former rebels count (was varchar)
            $table->string('status')->nullable();        // Konsolidado/Rekonsilida/Expansion/Recovery
            $table->string('infestation_color', 50)->nullable();
            $table->integer('rebels')->default(0);
            $table->timestamps();
        });

        Schema::create('color_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_barangay_id')->nullable()->constrained('map_barangays')->cascadeOnDelete();
            $table->string('status', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->integer('frs')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('color_histories');
        Schema::dropIfExists('map_barangays');
    }
};
