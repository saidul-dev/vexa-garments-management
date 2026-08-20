<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'party_id',
        'total_due',
        'total_bill',
        'total_paid',
        'category_name',
        'expense_description',
    ];

    public function party (): BelongsTo
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
}
