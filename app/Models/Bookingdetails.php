<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookingdetails extends Model
{
    use HasFactory;

    protected $fillable = [	'booking_id', 'data', 'cuff_color', 'collar_size_qty', 'cuff_solid'];

    protected $casts = [
        'data' => 'json',
        'cuff_color' => 'json',
        'collar_size_qty' => 'json',
        'cuff_solid' => 'json',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class,'booking_id');
    }
}
