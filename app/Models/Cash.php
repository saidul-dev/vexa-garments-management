<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cash extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'voucher_id', 'bank_id', 'amount', 'type', 'date', 'cash_type', 'description'];

    public function bank()
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }
}
