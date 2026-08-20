<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'user_id', 'order_info', 'cutting','print','input_line','output_line','finishing','poly','remarks'];

    protected $casts = [
        'order_info'    => 'json',
        'cutting'       => 'json',
        'print'         => 'json',
        'input_line'    => 'json',
        'output_line'   => 'json',
        'finishing'     => 'json',
        'poly'          => 'json',
    ];
    public function order (): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
