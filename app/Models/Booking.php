<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kost_id',
        'start_date',
        'duration_months',
        'total_price',
        'status',
    ];

    // Relasi ke User (Pencari yang memesan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kost
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}