@extends('backend.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-6">
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
        <!-- Stats Card 1 -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Total Users</p>
                        <h3 class="text-2xl font-bold text-gray-900">1,234</h3>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-users-alt text-primary text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-success text-sm">+12.5%</span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-900">$45,678</h3>
                    </div>
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-dollar-sign text-success text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-success text-sm">+8.2%</span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Stats Card 3 -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Total Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900">567</h3>
                    </div>
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-shopping-cart text-warning text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-danger text-sm">-3.5%</span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Stats Card 4 -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Total Products</p>
                        <h3 class="text-2xl font-bold text-gray-900">890</h3>
                    </div>
                    <div class="w-12 h-12 bg-info/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-box text-info text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-success text-sm">+5.7%</span>
                    <span class="text-gray-500 text-sm ml-2">from last month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="card">
        <div class="p-6">
            <h4 class="card-title mb-4">Welcome to TailFox Admin Dashboard!</h4>
            <p class="text-gray-600 mb-4">
                This is your admin dashboard. Here you can manage all aspects of your application.
                The dashboard has been cleanly divided into reusable components:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li><strong>Header:</strong> Located at [resources/views/backend/partials/header.blade.php](resources/views/backend/partials/header.blade.php)</li>
                <li><strong>Sidebar:</strong> Located at [resources/views/backend/partials/sidebar.blade.php](resources/views/backend/partials/sidebar.blade.php)</li>
                <li><strong>Footer:</strong> Located at [resources/views/backend/partials/footer.blade.php](resources/views/backend/partials/footer.blade.php)</li>
                <li><strong>Main Layout:</strong> Located at [resources/views/backend/layouts/master.blade.php](resources/views/backend/layouts/master.blade.php)</li>
            </ul>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="p-6">
            <h4 class="card-title mb-4">Recent Activity</h4>
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-user text-primary"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900">New user registered</h5>
                        <p class="text-sm text-gray-500">John Doe joined 5 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-success/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-shopping-bag text-success"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900">New order received</h5>
                        <p class="text-sm text-gray-500">Order #12345 placed 15 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-warning/10 rounded-full flex items-center justify-center">
                        <i class="uil uil-box text-warning"></i>
                    </div>
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-900">Product stock updated</h5>
                        <p class="text-sm text-gray-500">Product inventory updated 1 hour ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
