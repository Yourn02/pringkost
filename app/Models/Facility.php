<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $guarded = ['id'];

    public function kosts()
    {
        return $this->belongsToMany(Kost::class, 'facility_kost', 'facility_id', 'kost_id');
    }
}