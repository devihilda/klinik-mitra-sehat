<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Polyclinic extends Model
{
    protected $guarded = [];

    /**
     * Get the doctors associated with this polyclinic.
     */
    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }
}
