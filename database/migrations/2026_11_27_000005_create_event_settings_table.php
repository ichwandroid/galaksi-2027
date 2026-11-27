<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('event_year', 16)->default('2027');
            $table->string('event_date_label')->default('Sabtu, 16 Januari 2027');
            $table->string('open_gate_label', 64)->default('07.00 WIB');
            $table->string('venue_name')->default('Graha Cakrawala');
            $table->string('venue_city')->default('Universitas Negeri Malang');
            $table->string('ticket_form_url')->nullable();
            $table->string('tenant_url')->nullable();
            $table->string('sponsor_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_settings');
    }
};
