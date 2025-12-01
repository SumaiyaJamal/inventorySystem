<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Type and common fields
        'type',
        'quantity',
        'purchase_price',
        'selling_price',
        'supplier_name',
        'notes',
        
        // LED Lights & Bulbs fields
        'product_name',
        'brand',
        'wattage',
        'light_type',
        'color_temperature',
        'purchase_date',
        'warranty_months',
        
        // Spices fields
        'spice_name',
        'category',
        'weight',
        'manufactured_date',
        'expiry_date',
        'storage_instructions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'warranty_months' => 'integer',
        'purchase_date' => 'date',
        'manufactured_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the inventory type constants.
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            'spices' => 'Spices',
            'led_light' => 'LED Light & Bulbs',
        ];
    }
}



