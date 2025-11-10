<?php

namespace Database\Factories;

use App\Models\DailyReport;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyReport>
 */
class DailyReportFactory extends Factory
{
    protected $model = DailyReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportDate = Carbon::instance(fake()->dateTimeBetween('-1 month', 'now'));

        return [
            'project_id' => Project::factory(),
            'report_date' => $reportDate,
            'worker_count' => fake()->numberBetween(8, 60),
            'work_hours' => fake()->randomFloat(2, 6, 12),
            'completion_percentage' => fake()->randomFloat(2, 5, 95),
            'notes' => fake()->sentence(12),
            'notes_ar' => 'ملاحظات التقرير: ' . fake()->sentence(10),
            'created_by' => User::factory(),
        ];
    }
}

