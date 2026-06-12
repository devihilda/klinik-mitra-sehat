<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $guarded = [];

    /**
     * Get the patient that this medical record belongs to.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the officer/doctor who created/updated this medical record.
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
