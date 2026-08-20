<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessoryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'user_id',
        'party_id',
        'qty_unit',
        'unit_price',
        'ttl_amount',
        'accessory_id',
    ];

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(Accessory::class,'accessory_id');
    }
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class,'party_id');
    }
}
