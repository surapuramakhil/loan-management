<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'loan_id',
        'amount',
        'status',
    ];

    /**
     * Payment status constants
     */
    const STATUS_INITIATED = 'initiated';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the loan associated with the payment.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Scope query to only include payments with specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope query to only include overdue payments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_FAILED]);
    }

    /**
     * Mark payment as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now()
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed()
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'processed_at' => now()
        ]);
    }
}