<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'vicard_order';

    public $timestamps = true;

//    protected $casts = [
//        'created_at' => 'datetime:d-m-Y h:i:s'
//    ];
}
