<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            ['en' => 'Residential Building', 'ar' => 'مشروع مبنى سكني'],
            ['en' => 'Villa Project', 'ar' => 'مشروع فيلا'],
            ['en' => 'Commercial Complex', 'ar' => 'مجمع تجاري'],
            ['en' => 'Infrastructure Upgrade', 'ar' => 'تطوير البنية التحتية'],
            ['en' => 'Maintenance Program', 'ar' => 'برنامج صيانة'],
        ];

        $selected = fake()->randomElement($names);
        $startDate = Carbon::instance(fake()->dateTimeBetween('-6 months', '-1 month'));
        $endDate = (clone $startDate)->addDays(fake()->numberBetween(90, 240));

        return [
            'name' => $selected['en'],
            'name_ar' => $selected['ar'],
            'client_id' => User::factory(),
            'contract_number' => fake()->unique()->bothify('CN-####-####'),
            'contract_file' => null,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_budget' => fake()->randomFloat(2, 250000, 2000000),
            'status' => fake()->randomElement(['active', 'completed', 'archived']),
            'location' => fake()->city(),
            'location_ar' => 'مدينة ' . fake()->randomElement(['الرياض', 'جدة', 'الدمام', 'دبي', 'أبوظبي']),
            'description' => fake()->sentence(10),
            'description_ar' => 'وصف موجز للمشروع: ' . fake()->sentence(8),
            'created_by' => User::factory(),
        ];
    }
}

