<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_item_id', 'stock_requisition_id', 'type', 'quantity', 'note', 'created_by',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function requisition()
    {
        return $this->belongsTo(StockRequisition::class, 'stock_requisition_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}