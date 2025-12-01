<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user (salesman) who created the request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the warehouse manager who approved/rejected the request
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all items in this request
     */
    public function items()
    {
        return $this->hasMany(InventoryRequestItem::class);
    }

    /**
     * Get total requested quantity
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('requested_quantity');
    }
}
