<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoqItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'project_id',
        'item_code',
        'item_name',
        'item_name_ar',
        'unit',
        'unit_ar',
        'total_quantity',
        'executed_quantity',
        'unit_price',
        'total_price',
        'specifications',
        'specifications_ar',
        'approved_supplier',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_quantity' => 'decimal:2',
            'executed_quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the project that owns the BOQ item.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the timelines for the BOQ item.
     */
    public function timelines(): HasMany
    {
        return $this->hasMany(ProjectTimeline::class);
    }

    /**
     * Get the material deliveries for the BOQ item.
     */
    public function materialDeliveries(): HasMany
    {
        return $this->hasMany(MaterialDelivery::class);
    }
}
