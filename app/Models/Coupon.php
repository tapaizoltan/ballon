<?php

namespace App\Models;

use App\Enums\AircraftType;
use App\Enums\CouponStatus;
use App\Enums\CouponTypeVip;
use Filament\Actions\Action;
use App\Enums\CouponTypePrivate;
use App\Models\Scopes\ClientScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([ClientScope::class])]
class Coupon extends Model
{
    //use HasFactory;

    protected $casts = [
        'status' => CouponStatus::class,
        'vip' => CouponTypeVip::class,
        'private' => CouponTypePrivate::class,
        'aircraft_type' => AircraftType::class,
    ];

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function aircraftLocationPilots()
    {
        return $this->belongsToMany(AircraftLocationPilot::class, 'checkins', 'coupon_id', 'aircraft_location_pilot_id')->withPivot('status');
    }

    public function ticketType()
    {
        return $this->hasOne(Tickettype::class, 'id', 'tickettype_id');
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (in_array($this->status, [CouponStatus::CanBeUsed, CouponStatus::Gift]) && ($this->adult + $this->children == $this->passengers->count())) {
                    return true;
                }

                return false;
            },
        );
    }

    protected function missingData(): Attribute
    {
        return Attribute::make(
            get: function () {
                return (in_array($this->status, [CouponStatus::CanBeUsed, CouponStatus::Gift]) && $this->adult + $this->children) != $this->passengers->count();
            },
        );
    }

    public function scopeMissingData(Builder $query): void
    {
        $query->whereIn('status', [CouponStatus::CanBeUsed, CouponStatus::Gift])->has('passengers', '<', DB::raw('coupons.adult+coupons.children'));
    }

    protected function isUsed(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (count($this->aircraftLocationPilots->where('pivot.status', 1))) {
                    return true;
                }

                return false;
            },
        );
    }    

    protected function membersCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->adult + $this->children,
        );
    }
}