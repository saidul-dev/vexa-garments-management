<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['party_id','user_id','merchandiser_id','bank_id','order_no','title','status','image','department','fabrication','gsm','yarn_count','shipment_mode','payment_mode','year','season','description','meta','invoice_details','lc'];

    protected $casts = [
        'meta' => 'json',
        'invoice_details' => 'json',
    ];

    public function party (): BelongsTo
    {
        return $this->belongsTo(Party::class,'party_id');
    }
    public function merchandiser (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails (): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function booking (): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function costing (): HasOne
    {
        return $this->hasOne(Costing::class);
    }
    public function bank (): BelongsTo
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }
    public function sample(): HasOne
    {
        return $this->HasOne(Sample::class);
    }
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class,'order_id');
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class,'order_id');
    }
}
