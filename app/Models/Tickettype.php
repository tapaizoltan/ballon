<?php

namespace App\Models;

use App\Enums\TicketTypeVip;
use App\Enums\TicketTypePrivate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tickettype extends Model
{
    protected $casts = [
        'vip' => TicketTypeVip::class,
        'private' => TicketTypePrivate::class,
    ];

    //use HasFactory;
    /*
    public function aircrafts()
    {
        return $this->hasMany(Aircraft::class); // itt azt definiáltuk, hogy egy jegytípushoz több légijármű is tartozhat.
    }
    */
    public function aircrafts(): BelongsToMany
    {
        return $this->belongsToMany(Aircraft::class, 'tickettype_aircraft');
    }

    use SoftDeletes;
}
