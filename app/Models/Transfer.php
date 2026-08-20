<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['trx_id', 'voucher_id', 'user_id', 'bank_from', 'bank_to', 'amount', 'transfer_type', 'adjust_type', 'meta', 'date', 'note'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Transfer::withTrashed()->max('id') + 1;
            $model->trx_id = str_pad($model->id, 7, 0, STR_PAD_LEFT);
        });
    }

    public function sender_bank()
    {
        return $this->belongsTo(Bank::class, 'bank_from');
    }

    public function receiver_bank()
    {
        return $this->belongsTo(Bank::class, 'bank_to');
    }

    protected $casts = [
        'meta' => 'json',
    ];
}
