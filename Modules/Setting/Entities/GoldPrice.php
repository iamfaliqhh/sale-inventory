<?php
namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    protected $fillable = [
        'date',      // date of the price
        'type',      // 'sale' or 'buyback'
        'price',     // price per gram
        'note',      // optional
    ];

    public $timestamps = false;
}
