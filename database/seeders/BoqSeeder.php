<?php

namespace Database\Seeders;

use App\Models\BoqItem;
use App\Models\Project;
use Illuminate\Database\Seeder;

class BoqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            return;
        }

        $templates = [
            [
                'code' => 'EXC',
                'name' => 'Excavation Work',
                'name_ar' => 'أعمال الحفر',
                'unit' => 'm3',
                'unit_ar' => 'م3',
                'quantity' => 320,
                'unit_price' => 70,
                'specs' => 'Bulk excavation and disposal for raft foundation.',
                'specs_ar' => 'حفر ونقل كميات كبيرة لقاعدة حصيرية.',
                'supplier' => 'Desert Earth Movers',
            ],
            [
                'code' => 'CON',
                'name' => 'Concrete Work',
                'name_ar' => 'أعمال الخرسانة',
                'unit' => 'm3',
                'unit_ar' => 'م3',
                'quantity' => 450,
                'unit_price' => 210,
                'specs' => 'Supply and casting of ready-mix concrete C35.',
                'specs_ar' => 'توريد وصب خرسانة جاهزة من نوع C35.',
                'supplier' => 'Gulf Ready Mix',
            ],
            [
                'code' => 'TIL',
                'name' => 'Tiling Work',
                'name_ar' => 'أعمال البلاط',
                'unit' => 'm2',
                'unit_ar' => 'م2',
                'quantity' => 900,
                'unit_price' => 55,
                'specs' => 'Installation of porcelain tiles with waterproofing membrane.',
                'specs_ar' => 'تركيب بلاط بورسلان مع عازل مائي.',
                'supplier' => 'Arabian Finishes',
            ],
        ];

        foreach ($projects as $project) {
            foreach ($templates as $index => $template) {
                $quantity = $template['quantity'] + ($index * 25) + ($project->id * 10);
                $unitPrice = $template['unit_price'] + ($project->id * 3);

                $executedQuantity = match ($project->status) {
                    'completed' => $quantity,
                    'active' => round($quantity * 0.65, 2),
                    default => round($quantity * 0.35, 2),
                };

                BoqItem::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'item_code' => sprintf('P%03d-%s', $project->id, $template['code']),
                    ],
                    [
                        'item_name' => $template['name'],
                        'item_name_ar' => $template['name_ar'],
                        'unit' => $template['unit'],
                        'unit_ar' => $template['unit_ar'],
                        'total_quantity' => $quantity,
                        'executed_quantity' => $executedQuantity,
                        'unit_price' => $unitPrice,
                        'total_price' => round($quantity * $unitPrice, 2),
                        'specifications' => $template['specs'],
                        'specifications_ar' => $template['specs_ar'],
                        'approved_supplier' => $template['supplier'],
                    ]
                );
            }
        }
    }
}

