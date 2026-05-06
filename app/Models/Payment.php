<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_requisition_id', 'uploaded_by', 'amount', 'payment_method',
        'receipt_path', 'receipt_original_name', 'transaction_reference',
        'payment_date', 'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function requisition()
    {
        return $this->belongsTo(StockRequisition::class, 'stock_requisition_id');
    }

    public function accountant()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}