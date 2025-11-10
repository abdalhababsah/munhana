<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@admin.com')->value('id');
        $clientsByEmail = User::where('role', 'client')->pluck('id', 'email');

        if ($clientsByEmail->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        $projectsData = [
            [
                'name' => 'Residential Building',
                'name_ar' => 'مشروع مبنى سكني',
                'client_email' => 'client1@test.com',
                'contract_number' => 'CN-2024-001',
                'start_date' => $now->copy()->subMonths(6),
                'end_date' => $now->copy()->addMonths(8),
                'total_budget' => 1_650_000.00,
                'status' => 'active',
                'location' => 'Riyadh, Saudi Arabia',
                'location_ar' => 'الرياض، المملكة العربية السعودية',
                'description' => 'Construction of a mid-rise residential building with shared amenities.',
                'description_ar' => 'بناء مبنى سكني متوسط الارتفاع مع خدمات مشتركة.',
            ],
            [
                'name' => 'Luxury Villa Development',
                'name_ar' => 'مشروع فيلا فاخرة',
                'client_email' => 'client2@test.com',
                'contract_number' => 'CN-2024-002',
                'start_date' => $now->copy()->subMonths(4),
                'end_date' => $now->copy()->addMonths(10),
                'total_budget' => 980_000.00,
                'status' => 'active',
                'location' => 'Dubai, United Arab Emirates',
                'location_ar' => 'دبي، الإمارات العربية المتحدة',
                'description' => 'Design and construction of a bespoke villa with smart home systems.',
                'description_ar' => 'تصميم وبناء فيلا مبتكرة مع أنظمة منزل ذكي.',
            ],
            [
                'name' => 'Infrastructure Upgrade',
                'name_ar' => 'تطوير البنية التحتية',
                'client_email' => 'client3@test.com',
                'contract_number' => 'CN-2024-003',
                'start_date' => $now->copy()->subMonths(10),
                'end_date' => $now->copy()->subMonths(2),
                'total_budget' => 2_250_000.00,
                'status' => 'completed',
                'location' => 'Abu Dhabi, United Arab Emirates',
                'location_ar' => 'أبوظبي، الإمارات العربية المتحدة',
                'description' => 'Roadworks and utilities upgrade across the industrial district.',
                'description_ar' => 'أعمال طرق وتطوير خدمات في المنطقة الصناعية.',
            ],
            [
                'name' => 'Mixed-Use Complex',
                'name_ar' => 'مجمع متعدد الاستخدام',
                'client_email' => 'client1@test.com',
                'contract_number' => 'CN-2024-004',
                'start_date' => $now->copy()->subMonths(12),
                'end_date' => $now->copy()->subMonths(1),
                'total_budget' => 3_100_000.00,
                'status' => 'completed',
                'location' => 'Jeddah, Saudi Arabia',
                'location_ar' => 'جدة، المملكة العربية السعودية',
                'description' => 'Delivery of commercial and residential towers with podium parking.',
                'description_ar' => 'تنفيذ أبراج تجارية وسكنية مع مواقف متعددة الطوابق.',
            ],
            [
                'name' => 'Annual Maintenance Program',
                'name_ar' => 'برنامج الصيانة السنوي',
                'client_email' => 'client2@test.com',
                'contract_number' => 'CN-2024-005',
                'start_date' => $now->copy()->subMonths(18),
                'end_date' => $now->copy()->subMonths(6),
                'total_budget' => 540_000.00,
                'status' => 'archived',
                'location' => 'Al Khobar, Saudi Arabia',
                'location_ar' => 'الخبر، المملكة العربية السعودية',
                'description' => 'Preventive maintenance for a portfolio of retail properties.',
                'description_ar' => 'صيانة وقائية لمجموعة من العقارات التجارية.',
            ],
        ];

        foreach ($projectsData as $project) {
            $clientId = $clientsByEmail[$project['client_email']] ?? $clientsByEmail->first();

            Project::updateOrCreate(
                ['contract_number' => $project['contract_number']],
                [
                    'name' => $project['name'],
                    'name_ar' => $project['name_ar'],
                    'client_id' => $clientId,
                    'contract_file' => null,
                    'start_date' => $project['start_date'],
                    'end_date' => $project['end_date'],
                    'total_budget' => $project['total_budget'],
                    'status' => $project['status'],
                    'location' => $project['location'],
                    'location_ar' => $project['location_ar'],
                    'description' => $project['description'],
                    'description_ar' => $project['description_ar'],
                    'created_by' => $adminId ?? $clientId,
                ]
            );
        }
    }
}

