<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [
            'pages' => [],
            'projects' => [],
            'users' => [],
            'clients' => [],
        ];

        // Search Pages
        $pages = [
            [
                'title_en' => 'Dashboard',
                'title_ar' => 'لوحة التحكم',
                'url' => route('backend.dashboard'),
                'icon' => 'uil-estate'
            ],
            [
                'title_en' => 'All Projects',
                'title_ar' => 'جميع المشاريع',
                'keywords' => 'projects work jobs',
                'url' => route('backend.projects.index'),
                'icon' => 'uil-briefcase'
            ],
            [
                'title_en' => 'Users Management',
                'title_ar' => 'إدارة المستخدمين',
                'keywords' => 'users members staff workers admins',
                'url' => route('backend.users.index'),
                'icon' => 'uil-users-alt'
            ],
            [
                'title_en' => 'Daily Reports',
                'title_ar' => 'التقارير اليومية',
                'keywords' => 'reports daily progress log',
                'url' => route('backend.reports.daily.all'),
                'icon' => 'uil-clipboard-notes'
            ],
            [
                'title_en' => 'BOQ Items',
                'title_ar' => 'بنود جدول الكميات',
                'keywords' => 'boq bill of quantities cost',
                'url' => route('backend.boq.all'),
                'icon' => 'uil-list-ul'
            ],
            [
                'title_en' => 'Timelines',
                'title_ar' => 'الجداول الزمنية',
                'keywords' => 'timeline schedule plan chart',
                'url' => route('backend.timeline.all'),
                'icon' => 'uil-calender'
            ],
            [
                'title_en' => 'Contact Messages',
                'title_ar' => 'رسائل التواصل',
                'keywords' => 'contact messages leads inbox',
                'url' => route('backend.contacts.index'),
                'icon' => 'uil-envelope'
            ],
            [
                'title_en' => 'Maintenance Schedules',
                'title_ar' => 'جداول الصيانة',
                'keywords' => 'maintenance repair schedule',
                'url' => route('backend.maintenance-schedules.projects'),
                'icon' => 'uil-wrench'
            ],
            [
                'title_en' => 'Warranty Issues',
                'title_ar' => 'قضايا الضمان',
                'keywords' => 'warranty issues defects problems',
                'url' => route('backend.warranty-issues.projects'),
                'icon' => 'uil-shield-check'
            ],
        ];

        $currentLocale = app()->getLocale();

        foreach ($pages as $page) {
            // Search in English Title, Arabic Title, and Keywords
            if (
                stripos($page['title_en'], $query) !== false ||
                stripos($page['title_ar'], $query) !== false ||
                (isset($page['keywords']) && stripos($page['keywords'], $query) !== false)
            ) {
                // Return the localized title for display
                $page['title'] = $currentLocale === 'ar' ? $page['title_ar'] : $page['title_en'];
                // Clean up internal keys before sending to frontend
                unset($page['title_en'], $page['title_ar'], $page['keywords']);
                $results['pages'][] = $page;
            }
        }

        // Search Projects
        $projects = Project::where('name', 'like', "%{$query}%")
            ->orWhere('name_ar', 'like', "%{$query}%")
            ->orWhere('contract_number', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($projects as $project) {
            $results['projects'][] = [
                'title' => app()->getLocale() === 'ar' ? $project->name_ar : $project->name,
                'url' => route('backend.projects.show', $project->id),
                'icon' => 'uil-building'
            ];
        }

        // Search Users & Clients
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($users as $user) {
            $item = [
                'title' => $user->name,
                'subtitle' => $user->email, // Optional subtitle
                'url' => route('backend.users.show', $user->id),
                'icon' => 'uil-user'
            ];

            if ($user->role === 'client') {
                $results['clients'][] = $item;
            } else {
                $results['users'][] = $item;
            }
        }

        return response()->json($results);
    }
}
