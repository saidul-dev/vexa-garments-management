<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;
    
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'meta',
        'year',
        'month',
        'notes',
        'amount',
        'bank_id',
        'user_id',
        'due_salary',
        'voucher_id',
        'employee_id',
        'payment_method',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'json',
    ];
}