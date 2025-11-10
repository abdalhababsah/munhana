<?php

namespace Database\Factories;

use App\Models\BoqItem;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BoqItem>
 */
class BoqItemFactory extends Factory
{
    protected $model = BoqItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $units = [
            ['en' => 'm3', 'ar' => 'م3'],
            ['en' => 'm2', 'ar' => 'م2'],
            ['en' => 'pcs', 'ar' => 'قطعة'],
        ];

        $unit = fake()->randomElement($units);
        $totalQuantity = fake()->randomFloat(2, 10, 500);
        $unitPrice = fake()->randomFloat(2, 50, 500);

        return [
            'project_id' => Project::factory(),
            'item_code' => fake()->unique()->bothify('BOQ-###'),
            'item_name' => fake()->randomElement([
                'Excavation Work',
                'Concrete Work',
                'Formwork',
                'Reinforcement',
                'Tiling Work',
            ]),
            'item_name_ar' => fake()->randomElement([
                'أعمال الحفر',
                'أعمال الخرسانة',
                'أعمال الشدات',
                'أعمال التسليح',
                'أعمال البلاط',
            ]),
            'unit' => $unit['en'],
            'unit_ar' => $unit['ar'],
            'total_quantity' => $totalQuantity,
            'executed_quantity' => fake()->randomFloat(2, 0, $totalQuantity),
            'unit_price' => $unitPrice,
            'total_price' => round($totalQuantity * $unitPrice, 2),
            'specifications' => fake()->sentence(12),
            'specifications_ar' => 'تفاصيل العمل: ' . fake()->sentence(10),
            'approved_supplier' => fake()->company(),
        ];
    }
}

