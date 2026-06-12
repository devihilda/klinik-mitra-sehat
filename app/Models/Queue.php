<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    protected $guarded = [];

    /**
     * Get the patient associated with the queue.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor schedule associated with the queue.
     */
    public function doctorSchedule(): BelongsTo
    {
        return $this->belongsTo(DoctorSchedule::class);
    }

    /**
     * Get the polyclinic associated with the queue.
     */
    public function polyclinic(): BelongsTo
    {
        return $this->belongsTo(Polyclinic::class, 'poli_id');
    }

    /**
     * Get the doctor associated with the queue.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
