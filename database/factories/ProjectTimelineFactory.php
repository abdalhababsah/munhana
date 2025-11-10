<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectTimeline;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectTimeline>
 */
class ProjectTimelineFactory extends Factory
{
    protected $model = ProjectTimeline::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plannedStart = Carbon::instance(fake()->dateTimeBetween('-2 months', '+1 month'));
        $plannedEnd = (clone $plannedStart)->addDays(fake()->numberBetween(10, 45));

        $actualStart = fake()->boolean(70)
            ? Carbon::instance(fake()->dateTimeBetween($plannedStart, $plannedEnd))
            : null;
        $actualEnd = $actualStart
            ? Carbon::instance(fake()->dateTimeBetween($actualStart, (clone $actualStart)->addDays(30)))
            : null;

        return [
            'project_id' => Project::factory(),
            'activity_name' => fake()->sentence(3),
            'activity_name_ar' => 'نشاط ' . fake()->word(),
            'boq_item_id' => null,
            'planned_start_date' => $plannedStart,
            'planned_end_date' => $plannedEnd,
            'actual_start_date' => $actualStart,
            'actual_end_date' => $actualEnd,
            'duration_days' => max(1, $plannedStart->diffInDays($plannedEnd)),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'delayed']),
        ];
    }
}

