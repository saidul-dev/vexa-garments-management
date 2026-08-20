<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'unit_id', 'name', 'description', 'unit_price', 'status'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

}
