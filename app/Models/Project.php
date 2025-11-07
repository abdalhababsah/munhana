<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'name_ar',
        'client_id',
        'contract_number',
        'contract_file',
        'start_date',
        'end_date',
        'total_budget',
        'status',
        'location',
        'location_ar',
        'description',
        'description_ar',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_budget' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the client that owns the project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the creator of the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the timelines for the project.
     */
    public function timelines(): HasMany
    {
        return $this->hasMany(ProjectTimeline::class);
    }

    /**
     * Get the BOQ items for the project.
     */
    public function boqItems(): HasMany
    {
        return $this->hasMany(BoqItem::class);
    }

    /**
     * Get the daily reports for the project.
     */
    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }

    /**
     * Get the material deliveries for the project.
     */
    public function materialDeliveries(): HasMany
    {
        return $this->hasMany(MaterialDelivery::class);
    }

    /**
     * Get the site visits for the project.
     */
    public function siteVisits(): HasMany
    {
        return $this->hasMany(SiteVisit::class);
    }

    /**
     * Get the weekly plans for the project.
     */
    public function weeklyPlans(): HasMany
    {
        return $this->hasMany(WeeklyPlan::class);
    }

    /**
     * Get the site photos for the project.
     */
    public function sitePhotos(): HasMany
    {
        return $this->hasMany(SitePhoto::class);
    }

    /**
     * Get the financial claims for the project.
     */
    public function financialClaims(): HasMany
    {
        return $this->hasMany(FinancialClaim::class);
    }

    /**
     * Get the project costs for the project.
     */
    public function projectCosts(): HasMany
    {
        return $this->hasMany(ProjectCost::class);
    }

    /**
     * Get the warranty issues for the project.
     */
    public function warrantyIssues(): HasMany
    {
        return $this->hasMany(WarrantyIssue::class);
    }

    /**
     * Get the maintenance schedules for the project.
     */
    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }
}
