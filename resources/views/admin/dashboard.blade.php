@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Dashboard Overview</h1>
    <p class="text-dark-muted">Monitor your platform performance and metrics</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Users</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>+12% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <i class="fas fa-images"></i>
        </div>
        <div class="stat-value">{{ $totalPhotos }}</div>
        <div class="stat-label">Total Photos</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>+8% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <i class="fas fa-folder"></i>
        </div>
        <div class="stat-value">{{ $totalBoards }}</div>
        <div class="stat-label">Total Boards</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>+15% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <i class="fas fa-heart"></i>
        </div>
        <div class="stat-value">{{ $totalLikes + $totalSaves }}</div>
        <div class="stat-label">Total Interactions</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>+20% from last month</span>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- User Registration Chart -->
    <div class="chart-container">
        <div class="chart-title">
            <span>User Registration</span>
            <span class="chart-subtitle">Last 7 days</span>
        </div>
        <div class="simple-bar" id="userRegistrationBar"></div>
    </div>

    <!-- Photo Upload Chart -->
    <div class="chart-container">
        <div class="chart-title">
            <span>Photo Uploads</span>
            <span class="chart-subtitle">Last 7 days</span>
        </div>
        <div class="simple-bar" id="photoUploadBar"></div>
    </div>
</div>

<!-- Category Distribution -->
<div class="chart-container mb-8">
    <div class="chart-title">
        <span>Photo Categories Distribution</span>
        <span class="chart-subtitle">Current month</span>
    </div>
    <div class="donut-container">
        <div class="donut-chart">
            <canvas id="categoryDonut" width="150" height="150"></canvas>
        </div>
        <div class="donut-legend" id="categoryLegend"></div>
    </div>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Users -->
    <div class="chart-container">
        <div class="chart-title">
            <span>Recent Users</span>
            <a href="{{ route('admin.users') }}" class="text-sm text-purple-400 hover:text-purple-300">View All</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentUsers as $user)
                <div class="flex items-center justify-between p-3 rounded-lg transition">
                    <div class="flex items-center">
                        <img src="{{ $user->avatar ?: asset('images/default-avatar.png') }}"
                             alt="{{ $user->name }}"
                             class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <p class="font-medium text-dark-text">{{ $user->name }}</p>
                            <p class="text-xs text-dark-muted">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-dark-muted">{{ $user->created_at->diffForHumans() }}</p>
                        @if ($user->is_active)
                            <span class="inline-block mt-1 px-2 py-1 bg-green-900 bg-opacity-50 text-green-400 rounded-full text-xs">Active</span>
                        @else
                            <span class="inline-block mt-1 px-2 py-1 bg-red-900 bg-opacity-50 text-red-400 rounded-full text-xs">Inactive</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-dark-muted py-4">No users found</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Photos -->
    <div class="chart-container">
        <div class="chart-title">
            <span>Recent Photos</span>
            <a href="{{ route('admin.photos') }}" class="text-sm text-purple-400 hover:text-purple-300">View All</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentPhotos as $photo)
                <div class="flex items-center justify-between p-3 rounded-lg transition">
                    <div class="flex items-center">
                        <img src="{{ asset($photo->image_path) }}"
                             alt="{{ $photo->title }}"
                             class="w-12 h-12 object-cover rounded mr-3">
                        <div>
                            <p class="font-medium text-dark-text truncate max-w-xs">{{ $photo->title }}</p>
                            <p class="text-xs text-dark-muted">by {{ $photo->user->name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-dark-muted">{{ $photo->category }}</p>
                        @if ($photo->is_featured)
                            <span class="inline-block mt-1 px-2 py-1 bg-yellow-900 bg-opacity-50 text-yellow-400 rounded-full text-xs">Featured</span>
                        @else
                            <span class="inline-block mt-1 px-2 py-1 bg-gray-900 bg-opacity-50 text-gray-400 rounded-full text-xs">Regular</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-dark-muted py-4">No photos found</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Top Content Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Most Liked Photos -->
    <div class="chart-container">
        <div class="chart-title">
            <span>Most Liked Photos</span>
        </div>
        <div class="space-y-3">
            @forelse ($mostLikedPhotos as $index => $photo)
                <div class="flex items-center p-3 rounded-lg transition">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ $index + 1 }}
                    </div>
                    <img src="{{ asset($photo->image_path) }}"
                         alt="{{ $photo->title }}"
                         class="w-12 h-12 object-cover rounded mr-3">
                    <div class="flex-1">
                        <p class="font-medium text-dark-text truncate">{{ $photo->title }}</p>
                        <p class="text-xs text-dark-muted">by {{ $photo->user->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-400">{{ $photo->likes_count }}</p>
                        <p class="text-xs text-dark-muted">likes</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-dark-muted py-4">No liked photos found</p>
            @endforelse
        </div>
    </div>

    <!-- Most Saved Photos -->
    <div class="chart-container">
        <div class="chart-title">
            <span>Most Saved Photos</span>
        </div>
        <div class="space-y-3">
            @forelse ($mostSavedPhotos as $index => $photo)
                <div class="flex items-center p-3 rounded-lg transition">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ $index + 1 }}
                    </div>
                    <img src="{{ asset($photo->image_path) }}"
                         alt="{{ $photo->title }}"
                         class="w-12 h-12 object-cover rounded mr-3">
                    <div class="flex-1">
                        <p class="font-medium text-dark-text truncate">{{ $photo->title }}</p>
                        <p class="text-xs text-dark-muted">by {{ $photo->user->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-yellow-400">{{ $photo->saves_count }}</p>
                        <p class="text-xs text-dark-muted">saves</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-dark-muted py-4">No saved photos found</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

<!-- Tambahkan di atas @push('scripts') -->
@if(app()->environment('local'))
<div class="fixed bottom-4 left-4 bg-dark-card p-4 rounded-lg text-xs text-dark-text max-w-md z-50">
    <h3 class="font-bold mb-2">Debug Info:</h3>
    <p>User Registration Count: {{ $userRegistrationData->count() }}</p>
    <p>Photo Upload Count: {{ $photoUploadData->count() }}</p>
    <p>Category Count: {{ $categoryDistribution->count() }}</p>
    <details class="mt-2">
        <summary>Raw Data</summary>
        <pre class="text-xs mt-2">{{ json_encode([
            'userRegistration' => $userRegistrationData->toArray(),
            'photoUpload' => $photoUploadData->toArray(),
            'category' => $categoryDistribution->toArray()
        ], JSON_PRETTY_PRINT) }}</pre>
    </details>
</div>
@endif

@push('scripts')
<script>
// Debug function
function debugLog(message, data = null) {
    console.log('DEBUG:', message, data);
}

// Simple Bar Chart for User Registration
function createSimpleBar(containerId, data, labels) {
    debugLog('Creating bar chart', { containerId, data, labels });

    const container = document.getElementById(containerId);
    if (!container) {
        console.error('Container not found:', containerId);
        return;
    }

    container.innerHTML = ''; // Clear existing content

    if (!data || data.length === 0) {
        container.innerHTML = '<div class="flex items-center justify-center h-full text-dark-muted">No data available</div>';
        return;
    }

    const maxValue = Math.max(...data, 1); // Ensure max is at least 1

    data.forEach((value, index) => {
        const barItem = document.createElement('div');
        barItem.className = 'bar-item';

        const bar = document.createElement('div');
        bar.className = 'bar';
        const height = Math.max((value / maxValue) * 100, 5); // Minimum 5px height
        bar.style.height = `${height}%`;

        const barValue = document.createElement('div');
        barValue.className = 'bar-value';
        barValue.textContent = value;
        bar.appendChild(barValue);

        const barLabel = document.createElement('div');
        barLabel.className = 'bar-label';
        barLabel.textContent = labels[index] || '';

        barItem.appendChild(bar);
        barItem.appendChild(barLabel);
        container.appendChild(barItem);
    });

    debugLog('Bar chart created successfully');
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    debugLog('DOM loaded, initializing charts');

    try {
        // Parse data safely
        let userRegistrationData, userRegistrationLabels;
        let photoUploadData, photoUploadLabels;
        let categoryData;

        @php
            // Prepare data for JavaScript
            $userRegData = $userRegistrationData->toArray();
            $userRegLabels = collect($userRegistrationData->pluck('date'))->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            })->toArray();

            $photoUploadDataArr = $photoUploadData->toArray();
            $photoUploadLabelsArr = collect($photoUploadData->pluck('date'))->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            })->toArray();

            $categoryDataArr = $categoryDistribution->toArray();
        @endphp

        userRegistrationData = @json($userRegData);
        userRegistrationLabels = @json($userRegLabels);
        photoUploadData = @json($photoUploadDataArr);
        photoUploadLabels = @json($photoUploadLabelsArr);
        categoryData = @json($categoryDataArr);

        debugLog('Data parsed', {
            userRegistrationData,
            userRegistrationLabels,
            photoUploadData,
            photoUploadLabels,
            categoryData
        });

        // Create User Registration Bar Chart
        createSimpleBar('userRegistrationBar', userRegistrationData, userRegistrationLabels);

        // Create Photo Upload Bar Chart
        createSimpleBar('photoUploadBar', photoUploadData, photoUploadLabels);

        // Create Donut Chart for Categories
        if (categoryData && categoryData.length > 0) {
            createDonutChart(categoryData);
        } else {
            document.getElementById('categoryLegend').innerHTML = '<div class="text-dark-muted">No data available</div>';
        }

    } catch (error) {
        console.error('Error initializing charts:', error);
    }
});

// Donut Chart function
function createDonutChart(categoryData) {
    debugLog('Creating donut chart', categoryData);

    const categoryColors = [
        '#667eea',
        '#f093fb',
        '#4facfe',
        '#43e97b',
        '#fa709a',
        '#feca57'
    ];

    // Create donut chart using canvas
    const canvas = document.getElementById('categoryDonut');
    if (!canvas) {
        console.error('Canvas not found');
        return;
    }

    const ctx = canvas.getContext('2d');
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = 60;
    const innerRadius = 40;

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    let total = categoryData.reduce((sum, item) => sum + item.count, 0);
    let currentAngle = -Math.PI / 2;

    categoryData.forEach((item, index) => {
        const sliceAngle = (item.count / total) * 2 * Math.PI;

        // Draw outer arc
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
        ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true);
        ctx.closePath();
        ctx.fillStyle = categoryColors[index % categoryColors.length];
        ctx.fill();

        currentAngle += sliceAngle;
    });

    // Create legend
    const legendContainer = document.getElementById('categoryLegend');
    if (!legendContainer) {
        console.error('Legend container not found');
        return;
    }

    legendContainer.innerHTML = ''; // Clear existing content

    categoryData.forEach((item, index) => {
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';

        const legendColor = document.createElement('div');
        legendColor.className = 'legend-color';
        legendColor.style.backgroundColor = categoryColors[index % categoryColors.length];

        const legendText = document.createElement('div');
        legendText.className = 'legend-text';
        legendText.textContent = item.category;

        const legendValue = document.createElement('div');
        legendValue.className = 'legend-value';
        const percentage = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
        legendValue.textContent = `${item.count} (${percentage}%)`;

        legendItem.appendChild(legendColor);
        legendItem.appendChild(legendText);
        legendItem.appendChild(legendValue);
        legendContainer.appendChild(legendItem);
    });

    debugLog('Donut chart created successfully');
}
</script>
@endpush
