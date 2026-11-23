<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KostImage extends Model
{
    use HasFactory;

    // Mengizinkan mass assignment untuk kolom-kolom ini
    protected $guarded = ['id'];

    // Relasi balik ke tabel Kost
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}