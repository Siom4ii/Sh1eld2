<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Lookup tables: municipalities, barangays, government agencies.
// Sources: fr_municipal, fr_barangay, fr_gov_agency.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();                       // was fr_municipal_id
            $table->string('name', 100);        // was fr_municipal_name
            $table->timestamps();
        });

        Schema::create('barangays', function (Blueprint $table) {
            $table->id();                       // was fr_barangay_id
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);        // was fr_barangay_name
            $table->timestamps();
        });

        Schema::create('gov_agencies', function (Blueprint $table) {
            $table->id();                       // was fr_gov_agency_id
            $table->string('name');             // was fr_gov_agency_name
            $table->string('acronym', 50);      // was fr_gov_agency_acro
            $table->string('profile')->nullable(); // was fr_gov_agency_prof
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangays');
        Schema::dropIfExists('gov_agencies');
        Schema::dropIfExists('municipalities');
    }
};
