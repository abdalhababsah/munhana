<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectTimeline;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::with('boqItems')->get();

        if ($projects->isEmpty()) {
            return;
        }

        $activities = [
            ['en' => 'Mobilization & Site Setup', 'ar' => 'التجهيز والتعبئة'],
            ['en' => 'Structural Works', 'ar' => 'الأعمال الإنشائية'],
            ['en' => 'Architectural Finishes', 'ar' => 'أعمال التشطيبات'],
            ['en' => 'Testing & Commissioning', 'ar' => 'الاختبارات والتشغيل'],
        ];

        $statusMatrix = [
            'active' => ['completed', 'in_progress', 'in_progress', 'pending'],
            'completed' => ['completed', 'completed', 'completed', 'completed'],
            'archived' => ['completed', 'completed', 'completed', 'delayed'],
        ];

        foreach ($projects as $project) {
            $baseStart = Carbon::parse($project->start_date);
            $boqItems = $project->boqItems->values();

            foreach ($activities as $index => $activity) {
                $plannedStart = (clone $baseStart)->addWeeks($index * 4);
                $plannedEnd = (clone $plannedStart)->addWeeks(3);
                $duration = max(1, $plannedStart->diffInDays($plannedEnd));

                $status = $statusMatrix[$project->status][$index] ?? 'pending';
                $actualStart = in_array($status, ['completed', 'in_progress', 'delayed'], true)
                    ? (clone $plannedStart)->addDays(random_int(-3, 3))
                    : null;
                $actualEnd = $status === 'completed'
                    ? (clone $plannedEnd)->addDays(random_int(-2, 5))
                    : null;

                $boqItemId = $boqItems->isNotEmpty()
                    ? $boqItems[$index % $boqItems->count()]->id
                    : null;

                ProjectTimeline::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'activity_name' => $activity['en'],
                    ],
                    [
                        'activity_name_ar' => $activity['ar'],
                        'boq_item_id' => $index % 2 === 0 ? $boqItemId : null,
                        'planned_start_date' => $plannedStart,
                        'planned_end_date' => $plannedEnd,
                        'actual_start_date' => $actualStart,
                        'actual_end_date' => $actualEnd,
                        'duration_days' => $duration,
                        'status' => $status,
                    ]
                );
            }
        }
    }
}

