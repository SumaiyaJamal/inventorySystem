<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_request_id',
        'inventory_id',
        'requested_quantity',
    ];

    /**
     * Get the inventory request
     */
    public function inventoryRequest()
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    /**
     * Get the inventory item
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
