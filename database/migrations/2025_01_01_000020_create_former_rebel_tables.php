<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Former Rebel (FR) registry + related records.
// Sources: fr_registered, fr_program_status, fr_education_work,
// fr_location_history, fr_skills, fr_government_assistance.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('former_rebels', function (Blueprint $table) {
            $table->id();                                   // was fr_regis_id
            $table->string('classified_id')->index();       // was fr_classified_id (FR-#0001)
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename')->nullable();        // was fr_mname
            $table->string('nickname')->nullable();
            $table->enum('suffix', ['Jr.', 'Sr.', 'II', 'III'])->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Separated'])->nullable();
            $table->string('residential_address')->nullable();
            $table->string('placement_address')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_num', 15)->nullable();
            $table->string('batch_year', 50)->nullable();
            $table->enum('batch_section', ['1', '2'])->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->foreignId('barangay_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('municipality_id')->nullable()->constrained()->nullOnDelete();
            $table->string('province', 50)->nullable();
            $table->date('surrender_date')->nullable();
            $table->text('surrender_reason')->nullable();
            $table->date('registered_at')->nullable();       // was fr_regis_uploaded_at
            $table->enum('status', [
                'Active', 'On hold', 'Reintegrated', 'Inactive', 'Under Review',
                'Disengaged', 'Pending', 'Suspended', 'Completed', 'Deceased', 'Relocated',
            ])->default('Active');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('work_status', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('fr_program_statuses', function (Blueprint $table) {
            $table->id();                                    // was status_id
            $table->foreignId('former_rebel_id')->constrained()->cascadeOnDelete();
            $table->enum('reintegration_status', ['Not-Started', 'On-going', 'Completed'])->nullable();
            $table->date('reintegration_date')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->unique('former_rebel_id');               // one active status per FR
        });

        Schema::create('fr_education_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('former_rebel_id')->constrained()->cascadeOnDelete();
            $table->string('educational_attainment')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        });

        Schema::create('fr_location_histories', function (Blueprint $table) {
            $table->id();                                    // was history_id
            $table->foreignId('former_rebel_id')->constrained()->cascadeOnDelete();
            $table->string('placement_address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('fr_skills', function (Blueprint $table) {
            $table->id();                                    // was skill_id
            $table->foreignId('former_rebel_id')->constrained()->cascadeOnDelete();
            $table->string('skill_name');
            $table->enum('proficiency_level', ['Beginner', 'Intermediate', 'Advanced'])->nullable();
            $table->timestamps();
        });

        Schema::create('fr_government_assistances', function (Blueprint $table) {
            $table->id();                                    // was assistance_id
            $table->foreignId('former_rebel_id')->constrained()->cascadeOnDelete();
            $table->string('assistance_type')->nullable();
            $table->date('date_received')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->nullable();
            $table->string('certificate_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fr_government_assistances');
        Schema::dropIfExists('fr_skills');
        Schema::dropIfExists('fr_location_histories');
        Schema::dropIfExists('fr_education_works');
        Schema::dropIfExists('fr_program_statuses');
        Schema::dropIfExists('former_rebels');
    }
};
