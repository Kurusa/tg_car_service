<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model {

    protected $table = 'record';
    protected $fillable = ['chat_id', 'user_info', 'car_info', 'promo', 'date'];
    const UPDATED_AT = null;

}