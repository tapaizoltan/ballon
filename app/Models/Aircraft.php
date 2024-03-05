<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    //use HasFactory;
    public function tickettype()
    {
        return $this->belongsTo(Tickettype::class); //itt meghatározom, hogy minden egyes jegytípus hozzá tartozik egy léijárműhöz.
    }
}
