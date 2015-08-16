<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $table = 'weather';
    protected $guarded = [];

    public function getDates()
    {
        return ['created_at', 'updated_at', 'dt'];
    }
}
