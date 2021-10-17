<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'type_informations';

    protected $hidden= ['id', 'created_at', 'updated_at'];

}
