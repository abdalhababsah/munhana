<?php

namespace Database\Seeders;

use App\Models\DailyReport;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::where('status', 'active')->get();
        $workerIds = User::where('role', 'worker')->pluck('id')->all();

        if ($projects->isEmpty() || empty($workerIds)) {
            return;
        }

        $notes = [
            [
                'en' => 'Concrete pour completed with quality checks on site.',
                'ar' => 'تم صب الخرسانة مع تنفيذ فحوصات الجودة في الموقع.',
            ],
            [
                'en' => 'Masonry teams focused on blockwork for core walls.',
                'ar' => 'ركزت فرق البناء على أعمال البلوك للجدران الأساسية.',
            ],
            [
                'en' => 'MEP rough-ins installed along the east elevation.',
                'ar' => 'تم تركيب تمديدات الميكانيكا والكهرباء والسباكة على الواجهة الشرقية.',
            ],
            [
                'en' => 'Waterproofing inspection passed before backfilling.',
                'ar' => 'تمت الموافقة على أعمال العزل قبل الردم.',
            ],
        ];

        foreach ($projects as $project) {
            $reportDates = collect(range(0, 3))
                ->map(fn () => Carbon::now()->subDays(random_int(3, 25)))
                ->sort();

            foreach ($reportDates as $index => $date) {
                $note = $notes[$index % count($notes)];
                $baseProgress = 35 + ($index * 15);

                DailyReport::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'report_date' => $date->toDateString(),
                    ],
                    [
                        'worker_count' => random_int(15, 45),
                        'work_hours' => round(random_int(60, 110) / 10, 2),
                        'completion_percentage' => min(100, round($baseProgress + random_int(-5, 8), 2)),
                        'notes' => $note['en'] . ' Progress focused on ' . strtolower($project->name) . '.',
                        'notes_ar' => $note['ar'] . ' التركيز على تقدم ' . $project->name_ar . '.',
                        'created_by' => Arr::random($workerIds),
                    ]
                );
            }
        }
    }
}

