<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->foreignId('boq_item_id')->constrained()->restrictOnDelete();
            $table->date('delivery_date');
            $table->decimal('quantity', 15, 2);
            $table->string('supplier_name');
            $table->string('received_by');
            $table->text('notes')->nullable();
            $table->text('notes_ar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_deliveries');
    }
};
