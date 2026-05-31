@extends('layouts.main')

@section('content')

<nav id="sidebar" class="sidebar d-flex flex-column">

    <div>
        <div class="sidebar-brand">
            <img src="{{ asset('ASSETS/frog.png') }}" style="width:32px; height:32px;" alt="Logo">
            <span>Notes Manager</span>
        </div>

        <div class="mt-3">
            <a href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/user"><i class="bi bi-person"></i> User</a>
            <a href="/notes"><i class="bi bi-journal-text"></i> My Notes</a>
        </div>
    </div>


    <div class="mt-auto mb-3">
        <a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

</nav>

<div class="main">
   <header class="topbar">

    <div class="d-flex align-items-center">
        <div class="toggle-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </div>

        <nav class="breadcrumb-nav ms-3">
            <a href="#">Portal</a>
            <span class="sep">›</span>
            <span class="text-dark fw-semibold">Dashboard</span>
        </nav>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="text-end">
            <div class="small fw-semibold">{{ $user->name }}</div>
            <div class="small text-muted">{{ $user->email }}</div>
        </div>
        <a href="/profile" class="d-flex align-items-center text-decoration-none">
                <img src="{{ auth()->user()->profile_picture_base64 ?? (auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : asset('ASSETS/blank-pfp.png')) }}" alt="Profile" 
                     style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
            </a>
    <main class="content-wrapper">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card card-box bg-users p-4 text-black">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="opacity-75 mb-2">Total Users</h5>
                            <h2 class="display-6 fw-bold">{{ $totalUsers }}</h2>
                        </div>
                        <i class="bi bi-people-fill" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-box bg-notes p-4 text-black">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="opacity-75 mb-2">Total Notes</h5>
                            <h2 class="display-6 fw-bold">{{ $totalNotes }}</h2>
                        </div>
                        <i class="bi bi-journal-text" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold">User Growth</h5>
                        <span class="badge bg-primary">Monthly</span>
                    </div>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold">Notes Distribution</h5>
                        <span class="badge bg-success">Summary</span>
                    </div>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="noteChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </form>
</div>

@endsection

{{-- @push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="{{ asset('notes.css') }}">
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f5f5f5;
}

.sidebar {
  width: 250px;
  background: #2c3e50;
  color: white;
  padding: 20px;
  position: fixed;
  height: 100vh;
  left: 0;
  top: 0;
  z-index: 1000;
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: bold;
  margin-bottom: 20px;
}

.sidebar a {
  color: white;
  text-decoration: none;
  display: block;
  padding: 10px 0;
  margin-bottom: 10px;
}

.sidebar a:hover {
  color: #8ed39c;
}

.main-layout {
  margin-left: 250px;
}

.topbar {
  background: white;
  padding: 20px;
  border-bottom: 1px solid #eee;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.content-wrapper {
  padding: 30px;
}

.toggle-btn {
  cursor: pointer;
  font-size: 20px;
}

@media (max-width: 768px) {
  .sidebar {
    width: 0;
    overflow: hidden;
  }
  
  .sidebar.show {
    width: 250px;
  }
  
  .main {
    margin-left: 0;
  }
}
</style>
@endpush --}}

@push('scripts')
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('show');
}

document.addEventListener('click', function(event) {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.querySelector('.toggle-btn');
  if (window.innerWidth <= 992 && 
      !sidebar.contains(event.target) && 
      !toggleBtn.contains(event.target)) {
    sidebar.classList.remove('show');
  }
});

const dashboardLabels = @json($chartLabels);
const dashboardUserData = @json($userChartData);
const dashboardNoteData = @json($noteChartData);

if (document.getElementById('userChart')) {
  const userChart = new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
      labels: dashboardLabels,
      datasets: [{
        label: 'New Users',
        data: dashboardUserData,
        borderColor: '#4e73df',
        backgroundColor: 'rgba(78, 115, 223, 0.15)',
        fill: true,
        tension: 0.35,
        borderWidth: 3,
        pointRadius: 4,
        pointBackgroundColor: '#4e73df',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top', labels: { font: { size: 12, weight: 'bold' } } }
      },
      scales: {
        x: {
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: { stepSize: 5 },
          grid: { color: 'rgba(200,200,200,0.15)' }
        }
      }
    }
  });
}

if (document.getElementById('noteChart')) {
  const noteChart = new Chart(document.getElementById('noteChart'), {
    type: 'bar',
    data: {
      labels: dashboardLabels,
      datasets: [{
        label: 'Notes Created',
        data: dashboardNoteData,
        backgroundColor: 'rgba(28, 200, 138, 0.8)',
        borderColor: '#1cc88a',
        borderWidth: 2,
        borderRadius: 8,
        maxBarThickness: 40
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top', labels: { font: { size: 12, weight: 'bold' } } }
      },
      scales: {
        x: {
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: { stepSize: 5 },
          grid: { color: 'rgba(200,200,200,0.15)' }
        }
      }
    }
  });
}
</script>
@endpush