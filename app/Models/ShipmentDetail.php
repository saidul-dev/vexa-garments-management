<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qty',
        'size',
        'item',
        'style',
        'color',
        'total_cm',
        'total_sale',
        'shipment_id',
        'shipment_date',
    ];

    public function shipment() 
    {
        return $this->belongsTo(Shipment::class);
    }
}
