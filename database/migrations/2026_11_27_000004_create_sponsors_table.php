<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table): void {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('phone', 32);
            $table->string('email')->nullable();
            $table->string('tier', 32)->default('Silver')->index();
            $table->unsignedBigInteger('contribution_amount')->default(0);
            $table->string('status', 32)->default('prospect')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
