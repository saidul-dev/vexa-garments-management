<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meta',
        'type',
        'date',
        'amount',
        'status',
        'bank_id',
        'user_id',
        'bill_no',
        'remarks',
        'party_id',
        'is_profit',
        'bill_type',
        'income_id',
        'voucher_no',
        'expense_id',
        'particulars',
        'bill_amount',
        'prev_balance',
        'payment_method',
        'current_balance',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class,'party_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class,);
    }

    public function user()
    {
        return $this->belongsTo(User::class,);
    }

    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function cash (): HasOne
    {
        return $this->hasOne(Cash::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $id = Voucher::max('id') + 1;
            $model->user_id = auth()->id();
            $model->bill_no = $id;
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'json',
    ];

}
