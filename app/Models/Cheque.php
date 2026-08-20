<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cheque extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'amount',
        'status',
        'user_id',
        'bank_id',
        'party_id',
        'cheque_no',
        'voucher_id',
        'issue_date',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
