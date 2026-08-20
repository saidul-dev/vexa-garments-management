<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'user_id', 'consignee','header', 'consignee', 'styles', 'colors', 'items', 'types', 'sizes', 'quantities','status', 'reason'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'header' => 'json',
        'styles' => 'json',
        'colors' => 'json',
        'items' => 'json',
        'types' => 'json',
        'sizes' => 'json',
        'quantities' => 'json',
    ];

    public function order (): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
