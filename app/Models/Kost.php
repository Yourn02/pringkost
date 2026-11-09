<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
        protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(KostImage::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_kost', 'kost_id', 'facility_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function bookings()
{
    return $this->hasMany(Booking::class);
}
}