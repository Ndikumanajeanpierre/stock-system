<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitionComment extends Model
{
    use HasFactory;

    protected $fillable = ['stock_requisition_id', 'user_id', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requisition()
    {
        return $this->belongsTo(StockRequisition::class, 'stock_requisition_id');
    }
}