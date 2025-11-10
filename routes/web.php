<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BoqController;
use App\Http\Controllers\Backend\TimelineController;
use App\Http\Controllers\Backend\DailyReportController;
use App\Http\Controllers\Backend\MaterialDeliveryController;
use App\Http\Controllers\Backend\SiteVisitController;
use App\Http\Controllers\Backend\WeeklyPlanController;
use App\Http\Controllers\Backend\SitePhotoController;
use App\Http\Controllers\Backend\FinancialClaimController;
use App\Http\Controllers\Backend\MaintenanceScheduleController;
use App\Http\Controllers\Backend\ProjectCostController;
use App\Http\Controllers\Backend\WarrantyIssueController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Backend\ContactController as BackendContactController;
use App\Http\Controllers\Backend\WorkerAssignmentController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientIssueController;
use App\Http\Controllers\Client\ClientPhotoController;
use App\Http\Controllers\Client\ClientProjectController;
use App\Http\Controllers\Client\ClientReportController;
use App\Http\Controllers\Worker\WorkerDashboardController;
use App\Http\Controllers\Worker\WorkerMaterialController;
use App\Http\Controllers\Worker\WorkerPhotoController;
use App\Http\Controllers\Worker\WorkerReportController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

// Language Switcher Route
Route::post('/language/switch/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Main Dashboard - Points to Backend Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Backend Admin Routes
Route::prefix('backend')->name('backend.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects Management
    Route::resource('projects', ProjectController::class);
    Route::get('contacts', [BackendContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [BackendContactController::class, 'show'])->name('contacts.show');
    Route::put('contacts/{contact}', [BackendContactController::class, 'update'])->name('contacts.update');

    // BOQ Management
    Route::get('boq/all', [BoqController::class, 'allItems'])->name('boq.all');
    Route::get('projects/{project}/boq', [BoqController::class, 'index'])->name('boq.index');
    Route::get('projects/{project}/boq/create', [BoqController::class, 'create'])->name('boq.create');
    Route::post('boq', [BoqController::class, 'store'])->name('boq.store');
    Route::get('boq/{boq}/edit', [BoqController::class, 'edit'])->name('boq.edit');
    Route::put('boq/{boq}', [BoqController::class, 'update'])->name('boq.update');
    Route::delete('boq/{boq}', [BoqController::class, 'destroy'])->name('boq.destroy');

    // Timeline Management
    Route::get('timeline/all', [TimelineController::class, 'allActivities'])->name('timeline.all');
    Route::get('projects/{project}/timeline', [TimelineController::class, 'index'])->name('timeline.index');
    Route::get('projects/{project}/timeline/create', [TimelineController::class, 'create'])->name('timeline.create');
    Route::post('timeline', [TimelineController::class, 'store'])->name('timeline.store');
    Route::get('timeline/{timeline}/edit', [TimelineController::class, 'edit'])->name('timeline.edit');
    Route::put('timeline/{timeline}', [TimelineController::class, 'update'])->name('timeline.update');
    Route::delete('timeline/{timeline}', [TimelineController::class, 'destroy'])->name('timeline.destroy');
    Route::post('timeline/{timeline}/progress', [TimelineController::class, 'updateProgress'])->name('timeline.progress');

    // Daily Reports Management
    Route::get('reports/all', [DailyReportController::class, 'allReports'])->name('reports.daily.all');
    Route::get('projects/{project}/reports/daily', [DailyReportController::class, 'index'])->name('reports.daily.index');
    Route::get('projects/{project}/reports/daily/create', [DailyReportController::class, 'create'])->name('reports.daily.create');
    Route::post('reports/daily', [DailyReportController::class, 'store'])->name('reports.daily.store');
    Route::get('reports/daily/{report}', [DailyReportController::class, 'show'])->name('reports.daily.show');
    Route::get('reports/daily/{report}/edit', [DailyReportController::class, 'edit'])->name('reports.daily.edit');
    Route::put('reports/daily/{report}', [DailyReportController::class, 'update'])->name('reports.daily.update');
    Route::delete('reports/daily/{report}', [DailyReportController::class, 'destroy'])->name('reports.daily.destroy');

    // Material Deliveries Management
    Route::get('projects/{project}/materials', [MaterialDeliveryController::class, 'index'])->name('materials.index');
    Route::get('projects/{project}/materials/create', [MaterialDeliveryController::class, 'create'])->name('materials.create');
    Route::post('materials', [MaterialDeliveryController::class, 'store'])->name('materials.store');
    Route::get('materials/{material}/edit', [MaterialDeliveryController::class, 'edit'])->name('materials.edit');
    Route::put('materials/{material}', [MaterialDeliveryController::class, 'update'])->name('materials.update');
    Route::delete('materials/{material}', [MaterialDeliveryController::class, 'destroy'])->name('materials.destroy');

    // Site Visits Management
    Route::get('projects/{project}/visits', [SiteVisitController::class, 'index'])->name('visits.index');
    Route::get('projects/{project}/visits/create', [SiteVisitController::class, 'create'])->name('visits.create');
    Route::post('visits', [SiteVisitController::class, 'store'])->name('visits.store');
    Route::get('visits/{visit}', [SiteVisitController::class, 'show'])->name('visits.show');
    Route::get('visits/{visit}/edit', [SiteVisitController::class, 'edit'])->name('visits.edit');
    Route::put('visits/{visit}', [SiteVisitController::class, 'update'])->name('visits.update');
    Route::delete('visits/{visit}', [SiteVisitController::class, 'destroy'])->name('visits.destroy');

    // Weekly Plans Management
    Route::get('projects/{project}/weekly-plans', [WeeklyPlanController::class, 'index'])->name('weekly-plans.index');
    Route::get('projects/{project}/weekly-plans/create', [WeeklyPlanController::class, 'create'])->name('weekly-plans.create');
    Route::post('weekly-plans', [WeeklyPlanController::class, 'store'])->name('weekly-plans.store');
    Route::get('weekly-plans/{weeklyPlan}', [WeeklyPlanController::class, 'show'])->name('weekly-plans.show');
    Route::get('weekly-plans/{weeklyPlan}/edit', [WeeklyPlanController::class, 'edit'])->name('weekly-plans.edit');
    Route::put('weekly-plans/{weeklyPlan}', [WeeklyPlanController::class, 'update'])->name('weekly-plans.update');
    Route::delete('weekly-plans/{weeklyPlan}', [WeeklyPlanController::class, 'destroy'])->name('weekly-plans.destroy');

    // Site Photos Management
    Route::get('projects/{project}/photos', [SitePhotoController::class, 'index'])->name('photos.index');
    Route::get('projects/{project}/photos/create', [SitePhotoController::class, 'create'])->name('photos.create');
    Route::post('photos', [SitePhotoController::class, 'store'])->name('photos.store');
    Route::get('photos/{photo}', [SitePhotoController::class, 'show'])->name('photos.show');
    Route::delete('photos/{photo}', [SitePhotoController::class, 'destroy'])->name('photos.destroy');

    // Post-Completion: Warranty Issues
    Route::get('warranty-issues/projects', [WarrantyIssueController::class, 'projectSelection'])->name('warranty-issues.projects');
    Route::get('projects/{project}/warranty-issues', [WarrantyIssueController::class, 'index'])->name('warranty-issues.index');
    Route::get('projects/{project}/warranty-issues/create', [WarrantyIssueController::class, 'create'])->name('warranty-issues.create');
    Route::post('warranty-issues', [WarrantyIssueController::class, 'store'])->name('warranty-issues.store');
    Route::get('warranty-issues/{warrantyIssue}', [WarrantyIssueController::class, 'show'])->name('warranty-issues.show');
    Route::put('warranty-issues/{warrantyIssue}', [WarrantyIssueController::class, 'update'])->name('warranty-issues.update');
    Route::delete('warranty-issues/{warrantyIssue}', [WarrantyIssueController::class, 'destroy'])->name('warranty-issues.destroy');

    // Post-Completion: Maintenance Schedules
    Route::get('maintenance-schedules/projects', [MaintenanceScheduleController::class, 'projectSelection'])->name('maintenance-schedules.projects');
    Route::get('projects/{project}/maintenance-schedules', [MaintenanceScheduleController::class, 'index'])->name('maintenance-schedules.index');
    Route::get('projects/{project}/maintenance-schedules/create', [MaintenanceScheduleController::class, 'create'])->name('maintenance-schedules.create');
    Route::post('maintenance-schedules', [MaintenanceScheduleController::class, 'store'])->name('maintenance-schedules.store');
    Route::put('maintenance-schedules/{maintenanceSchedule}', [MaintenanceScheduleController::class, 'update'])->name('maintenance-schedules.update');
    Route::delete('maintenance-schedules/{maintenanceSchedule}', [MaintenanceScheduleController::class, 'destroy'])->name('maintenance-schedules.destroy');

    // Financial Claims Management
    Route::get('projects/{project}/claims', [FinancialClaimController::class, 'index'])->name('claims.index');
    Route::get('projects/{project}/claims/create', [FinancialClaimController::class, 'create'])->name('claims.create');
    Route::post('claims', [FinancialClaimController::class, 'store'])->name('claims.store');
    Route::get('claims/{claim}', [FinancialClaimController::class, 'show'])->name('claims.show');
    Route::get('claims/{claim}/edit', [FinancialClaimController::class, 'edit'])->name('claims.edit');
    Route::put('claims/{claim}', [FinancialClaimController::class, 'update'])->name('claims.update');
    Route::delete('claims/{claim}', [FinancialClaimController::class, 'destroy'])->name('claims.destroy');

    // Project Costs Management
    Route::get('projects/{project}/costs', [ProjectCostController::class, 'index'])->name('costs.index');
    Route::get('projects/{project}/costs/create', [ProjectCostController::class, 'create'])->name('costs.create');
    Route::post('costs', [ProjectCostController::class, 'store'])->name('costs.store');
    Route::get('costs/{cost}/edit', [ProjectCostController::class, 'edit'])->name('costs.edit');
    Route::put('costs/{cost}', [ProjectCostController::class, 'update'])->name('costs.update');
    Route::delete('costs/{cost}', [ProjectCostController::class, 'destroy'])->name('costs.destroy');

    // Users Management
    Route::resource('users', UserController::class);

    // Worker Assignments
    Route::get('workers', [WorkerAssignmentController::class, 'allWorkers'])->name('workers.index');
    Route::get('workers/{worker}', [WorkerAssignmentController::class, 'show'])->name('workers.show');
    Route::get('projects/{project}/workers', [WorkerAssignmentController::class, 'index'])->name('workers.assignments');
    Route::post('projects/{project}/workers', [WorkerAssignmentController::class, 'store'])->name('workers.assignments.store');
    Route::put('projects/{project}/workers/{worker}', [WorkerAssignmentController::class, 'update'])->name('workers.assignments.update');
    Route::delete('projects/{project}/workers/{worker}', [WorkerAssignmentController::class, 'destroy'])->name('workers.assignments.destroy');

});

// Shared Comments Routes (accessible to all authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('backend/comments', [CommentController::class, 'store'])->name('backend.comments.store');
    Route::put('backend/comments/{comment}', [CommentController::class, 'update'])->name('backend.comments.update');
    Route::delete('backend/comments/{comment}', [CommentController::class, 'destroy'])->name('backend.comments.destroy');
});

// Client Portal Routes
Route::prefix('client')->name('client.')->middleware(['auth', 'verified', 'client'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // Projects (Read-Only)
    Route::get('/projects', [ClientProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ClientProjectController::class, 'show'])->name('projects.show');

    // Reports (Read-Only)
    Route::get('/projects/{project}/reports/daily', [ClientReportController::class, 'dailyReports'])->name('reports.daily');
    Route::get('/reports/daily/{report}', [ClientReportController::class, 'showDailyReport'])->name('reports.daily.show');
    Route::get('/projects/{project}/reports/material-deliveries', [ClientReportController::class, 'materialDeliveries'])->name('reports.material-deliveries');
    Route::get('/projects/{project}/reports/site-visits', [ClientReportController::class, 'siteVisits'])->name('reports.site-visits');
    Route::get('/projects/{project}/reports/weekly-plans', [ClientReportController::class, 'weeklyPlans'])->name('reports.weekly-plans');

    // Photos (Read-Only)
    Route::get('/projects/{project}/photos', [ClientPhotoController::class, 'index'])->name('photos.index');
    Route::get('/photos/{photo}', [ClientPhotoController::class, 'show'])->name('photos.show');

    // Issues
    Route::get('/issues', [ClientIssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/projects', [ClientIssueController::class, 'projects'])->name('issues.projects');
    Route::get('/projects/{project}/issues/create', [ClientIssueController::class, 'create'])->name('issues.create');
    Route::post('/issues', [ClientIssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{issue}', [ClientIssueController::class, 'show'])->name('issues.show');
});

// Worker Portal Routes
Route::prefix('worker')->name('worker.')->middleware(['auth', 'verified', 'worker'])->group(function () {
    Route::get('/dashboard', [WorkerDashboardController::class, 'index'])->name('dashboard');

    // Projects - List and View (with worker.project middleware for show)
    Route::get('/projects', [\App\Http\Controllers\Worker\WorkerProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [\App\Http\Controllers\Worker\WorkerProjectController::class, 'show'])
        ->middleware('worker.project')
        ->name('projects.show');

    // Daily Reports (with worker.project middleware)
    Route::get('/projects/{project}/reports/create', [WorkerReportController::class, 'create'])
        ->middleware('worker.project')
        ->name('reports.create');
    Route::post('/reports', [WorkerReportController::class, 'store'])->name('reports.store');
    Route::get('/projects/{project}/reports/{report}', [WorkerReportController::class, 'show'])
        ->middleware('worker.project')
        ->name('reports.show');

    // Material Deliveries (with worker.project middleware)
    Route::get('/projects/{project}/materials/create', [WorkerMaterialController::class, 'create'])
        ->middleware('worker.project')
        ->name('materials.create');
    Route::post('/materials', [WorkerMaterialController::class, 'store'])->name('materials.store');

    // Site Photos (with worker.project middleware)
    Route::get('/projects/{project}/photos/create', [WorkerPhotoController::class, 'create'])
        ->middleware('worker.project')
        ->name('photos.create');
    Route::post('/photos', [WorkerPhotoController::class, 'store'])->name('photos.store');
});

// Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
