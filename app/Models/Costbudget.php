<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Costbudget extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'user_id', 'order_info', 'pre_cost_date','post_cost_date','image','booking_no','yarn_desc','yarn_cost','yarn_qty','knitting_desc','knitting_cost','knitting_qty','dfa_desc','dfa_cost','dfa_qty','fabric_cost','fabric_qty','accessories_desc','accessories_cost','accessories_qty','other_cost', 'grand_cost','pre_cost_desc','finance_value','finance_cost','finance_pre_cost','deferred_value','deferred_cost','deferred_pre_cost','factory_cm_cost','factory_cm_pre_cost','total_making_cost','total_making_pre_cost','total_expense_cost','total_expense_pre_cost','net_earning_cost','net_earning_pre_cost','status','meta'];

    protected $casts = [
        'meta' =>'json',
        'dfa_desc' =>'json',
        'order_info'=> 'json',
        'yarn_desc' =>'json',
        'knitting_desc' =>'json',
        'pre_cost_desc' =>'json',
        'accessories_desc' =>'json',
    ];

    public function order (): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
