<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Patient extends Model
{
    use SoftDeletes;

    // Kebutuhan Praktikum: Menggunakan guarded kosong untuk memicu celah Mass Assignment
    protected $guarded = [];

    /**
     * Boot the model to automatically strip out attributes that do not exist
     * in the database schema. This allows using $request->all() directly for
     * Mass Assignment simulation without throwing SQL Column Not Found exceptions.
     */
    protected static function booted(): void
    {
        static::saving(function ($patient) {
            $columns = Schema::getColumnListing($patient->getTable());
            if (! empty($columns)) {
                $patient->attributes = array_intersect_key($patient->attributes, array_flip($columns));
            }
        });
    }

    /**
     * Get the user that owns the patient details.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
