<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // finished product
            $table->foreignId('component_id')->constrained('products')->onDelete('cascade'); // component
            $table->decimal('quantity', 14, 3);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['product_id', 'component_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_of_materials');
    }
};
