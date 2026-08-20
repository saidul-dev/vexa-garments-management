<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    protected $fillable = ['party_id', 'category_name', 'total_bill', 'total_paid', 'total_due', 'income_description', 'status'];

    public function party (): BelongsTo
    {
        return $this->belongsTo(Party::class,'party_id');
    }
}
