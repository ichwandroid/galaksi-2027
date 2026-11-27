<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('phone', 32);
            $table->string('email')->nullable();
            $table->string('category', 64)->default('Kuliner');
            $table->string('booth_code', 32)->nullable();
            $table->string('status', 32)->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
