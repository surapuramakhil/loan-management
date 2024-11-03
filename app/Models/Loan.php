<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lender_id',
        'borrower_id',
        'amount',
        'interest_rate',
        'duration_years'
    ];

    protected $hidden = [
        'deleted_at'  
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'duration_years' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
    
}