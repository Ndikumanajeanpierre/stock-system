<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number', 'user_id', 'department_id', 'item_name',
        'quantity', 'unit', 'description', 'priority', 'status',
        'estimated_cost', 'actual_cost', 'rejection_reason',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'approved'  => 'info',
            'rejected'  => 'danger',
            'purchased' => 'primary',
            'paid'      => 'success',
            'completed' => 'dark',
            default     => 'secondary',
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'urgent' => 'danger',
            'high'   => 'warning',
            'medium' => 'primary',
            'low'    => 'secondary',
            default  => 'secondary',
        };
    }

    protected static function booted(): void
    {
        static::creating(function (StockRequisition $req) {
            $year  = now()->year;
            $count = static::whereYear('created_at', $year)->count() + 1;
            $req->reference_number = 'REQ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }
}