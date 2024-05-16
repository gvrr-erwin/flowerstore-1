<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'itemType', 'quantity', 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
