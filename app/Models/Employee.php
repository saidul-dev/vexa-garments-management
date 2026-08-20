<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['designation_id', 'name', 'email', 'address', 'gender', 'phone', 'salary', 'employee_type', 'join_date', 'birth_date', 'status', 'meta'];

    protected $casts = [
        'meta' => 'json'
    ];

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
