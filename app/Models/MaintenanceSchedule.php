<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'project_id',
        'maintenance_date',
        'maintenance_type',
        'maintenance_type_ar',
        'status',
        'notes',
        'notes_ar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'maintenance_date' => 'date',
        ];
    }

    /**
     * Get the project that owns the maintenance schedule.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
