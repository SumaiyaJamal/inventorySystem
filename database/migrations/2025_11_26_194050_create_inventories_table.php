<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            
            // Type field: 'spices' or 'led_light'
            $table->enum('type', ['spices', 'led_light']);
            
            // Common fields
            $table->integer('quantity');
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->string('supplier_name');
            $table->text('notes')->nullable();
            
            // Fields for LED Lights & Bulbs (nullable for spices)
            $table->string('product_name')->nullable(); // Tube Light / Bulb
            $table->string('brand')->nullable();
            $table->string('wattage')->nullable(); // e.g., 18W, 36W
            $table->string('light_type')->nullable(); // LED / CFL / Halogen / Tube
            $table->string('color_temperature')->nullable(); // Warm, Cool, Daylight
            $table->date('purchase_date')->nullable();
            $table->integer('warranty_months')->nullable();
            
            // Fields for Spices (nullable for LED lights)
            $table->string('spice_name')->nullable();
            $table->string('category')->nullable(); // Whole / Powder / Mix
            $table->string('weight')->nullable(); // 250g, 1kg etc
            $table->date('manufactured_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('storage_instructions')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
