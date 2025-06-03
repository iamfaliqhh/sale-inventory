<?php
namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    protected $fillable = [
        'transaction_price',
        'trade_in_price',
        'buyback_price',
    ];
}
