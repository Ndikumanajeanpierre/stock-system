<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'unit', 'unit_price',
        'quantity_available', 'description', 'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'unit_price'   => 'decimal:2',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function totalIn()
    {
        return $this->movements()->where('type', 'in')->sum('quantity');
    }

    public function totalOut()
    {
        return $this->movements()->where('type', 'out')->sum('quantity');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('quantity_available', '>', 0);
    }
}