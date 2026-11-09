<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Mengizinkan mass assignment
    protected $fillable = ['user_id', 'kost_id'];

    // Relasi ke User (mahasiswa yang menyimpan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kost yang disimpan
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}