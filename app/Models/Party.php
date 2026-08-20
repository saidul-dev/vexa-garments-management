<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type', 'creator_id', 'user_id', 'currency_id', 'address', 'total_bill', 'balance', 'advance_amount', 'due_amount', 'pay_amount', 'opening_balance', 'opening_balance_type', 'remarks', 'receivable_type', 'meta'];

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function vouchers(){
        return $this->hasMany(Voucher::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    protected $casts = [
        'meta' => 'json',
    ];
}
