<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ruangan_id',
        'start_book',
        'end_book',
        'approved_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,  "user_id");
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, "ruangan_id");
    }

    public static function isDatetimeAvailable($start_book, $end_book)
    {
        // Add your logic here to check if the datetimes are available
        // For example, check if there are no overlapping bookings for the given datetimes
        $existingBooking = self::where(function ($query) use ($start_book, $end_book) {
            $query->where('start_book', '<', $end_book)
                ->where('end_book', '>', $start_book);
        })->exists();

        return !$existingBooking;
    }
}
