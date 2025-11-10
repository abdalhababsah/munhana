<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the projects where user is the client.
     */
    public function clientProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Get the projects created by the user.
     */
    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get the daily reports created by the user.
     */
    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class, 'created_by');
    }

    /**
     * Get the site visits created by the user.
     */
    public function siteVisits(): HasMany
    {
        return $this->hasMany(SiteVisit::class, 'created_by');
    }

    /**
     * Get the weekly plans created by the user.
     */
    public function weeklyPlans(): HasMany
    {
        return $this->hasMany(WeeklyPlan::class, 'created_by');
    }

    /**
     * Get the site photos uploaded by the user.
     */
    public function sitePhotos(): HasMany
    {
        return $this->hasMany(SitePhoto::class, 'uploaded_by');
    }

    /**
     * Get the financial claims submitted by the user.
     */
    public function financialClaims(): HasMany
    {
        return $this->hasMany(FinancialClaim::class, 'submitted_by');
    }

    /**
     * Get the warranty issues reported by the user.
     */
    public function warrantyIssues(): HasMany
    {
        return $this->hasMany(WarrantyIssue::class, 'reported_by');
    }

    /**
     * Get the comments created by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the projects where this user is assigned as a worker.
     */
    public function assignedProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withPivot('assigned_by', 'assigned_at', 'role_description', 'role_description_ar')
            ->withTimestamps();
    }
}
