<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','order_id','booking_date','status','composition','meta','reason', 'header'];

    protected $casts = [
        'meta' => 'json',
        'header' => 'json',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $id = Booking::max('id') + 1;
            $bookingNo = "BK-" . str_pad($id, 4, '0', STR_PAD_LEFT);
            $model->booking_no = $bookingNo;
        });
    }
    
    public function order (): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function detail ()
    {
        return $this->belongsTo(Bookingdetails::class, 'id', 'booking_id');
    }
}
