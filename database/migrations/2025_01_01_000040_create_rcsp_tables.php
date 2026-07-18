<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RCSP (Retooled Community Support Program) phased monitoring workflow.
// Sources: fr_rscp_phase, fr_rscp_activity, fr_rscp_barangay,
// rscp_phases_status, fr_rscp_form, fr_rscp_file_comments, fr_rscp_lgu_comments.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rcsp_phases', function (Blueprint $table) {
            $table->id();                        // was fr_rcsp_phase_id
            $table->string('name');              // was fr_rcsp_phase_name
            $table->unsignedTinyInteger('number'); // was fr_rcsp_phase_num (0..5)
            $table->timestamps();
        });

        Schema::create('rcsp_activities', function (Blueprint $table) {
            $table->id();                        // was fr_rcsp_activity_id
            $table->foreignId('rcsp_phase_id')->constrained('rcsp_phases')->cascadeOnDelete();
            $table->text('description');         // was fr_rcsp_activity_des
            $table->timestamps();
        });

        Schema::create('rcsp_barangays', function (Blueprint $table) {
            $table->id();                        // was fr_rscp_bgy_id
            $table->foreignId('barangay_id')->constrained()->cascadeOnDelete();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['Pending', 'Ongoing', 'Completed'])->default('Pending');
            $table->unsignedTinyInteger('current_phase')->default(0);
            $table->timestamps();
        });

        Schema::create('rcsp_phase_statuses', function (Blueprint $table) {
            $table->id();                        // was fr_rscp_phase_status_id
            $table->foreignId('rcsp_barangay_id')->constrained('rcsp_barangays')->cascadeOnDelete();
            $table->boolean('phase0_completed')->default(false);
            $table->boolean('phase1_completed')->default(false);
            $table->boolean('phase2_completed')->default(false);
            $table->boolean('phase3_completed')->default(false);
            $table->boolean('phase4_completed')->default(false);
            $table->boolean('phase5_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('rcsp_forms', function (Blueprint $table) {
            $table->id();                        // was fr_rcsp_form_id
            $table->foreignId('lgu_user_id')->constrained('users')->cascadeOnDelete(); // was fr_lgu_id
            $table->foreignId('rcsp_barangay_id')->constrained('rcsp_barangays')->cascadeOnDelete();
            $table->foreignId('rcsp_phase_id')->constrained('rcsp_phases')->cascadeOnDelete();
            $table->foreignId('rcsp_activity_id')->constrained('rcsp_activities')->cascadeOnDelete();
            $table->enum('conduct', ['yes', 'no', 'n/a'])->nullable();
            $table->string('file')->nullable();  // was fr_rcsp_form_file
            $table->enum('status', [
                'ongoing', 'approved', 'disapproved', 'not yet started', 'draft',
                'submitted', 'updated', 'to be complied', 'to be conducted',
            ])->default('submitted');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('rcsp_file_comments', function (Blueprint $table) {
            $table->id();                        // was fr_rcsp_com_id
            $table->foreignId('rcsp_form_id')->nullable()->constrained('rcsp_forms')->nullOnDelete();
            $table->foreignId('rcsp_phase_id')->nullable()->constrained('rcsp_phases')->nullOnDelete();
            $table->foreignId('rcsp_activity_id')->nullable()->constrained('rcsp_activities')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('text');
            $table->timestamps();
        });

        Schema::create('rcsp_lgu_comments', function (Blueprint $table) {
            $table->id();                        // was fr_rcsp_lgu_com_id
            $table->foreignId('rcsp_form_id')->nullable()->constrained('rcsp_forms')->nullOnDelete();
            $table->foreignId('rcsp_phase_id')->nullable()->constrained('rcsp_phases')->nullOnDelete();
            $table->foreignId('rcsp_activity_id')->nullable()->constrained('rcsp_activities')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rcsp_lgu_comments');
        Schema::dropIfExists('rcsp_file_comments');
        Schema::dropIfExists('rcsp_forms');
        Schema::dropIfExists('rcsp_phase_statuses');
        Schema::dropIfExists('rcsp_barangays');
        Schema::dropIfExists('rcsp_activities');
        Schema::dropIfExists('rcsp_phases');
    }
};
