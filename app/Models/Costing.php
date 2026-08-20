<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Costing extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'user_id' ,'buying_commission', 'order_info', 'yarn_desc','yarn_total','knitting_desc','knitting_total','dyeing_desc','dyeing_total','print_desc','print_total','trim_desc','trim_total','commercial_qty','commercial_unit','commercial_price','commercial_total','cm_cost_composition','cm_cost_qty','cm_cost_unit','cm_cost_price','cm_cost_total','grand_total','grand_total_in_dzn','status','reason'];

    protected $casts = [
        'order_info'     => 'json',
        'yarn_desc'     => 'json',
        'knitting_desc' => 'json',
        'dyeing_desc'   => 'json',
        'print_desc'    => 'json',
        'trim_desc'     => 'json',
    ];

    public function order (): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
