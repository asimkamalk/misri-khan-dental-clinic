{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Misri Khan Dental Clinic') }} Admin</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <!-- Additional CSS -->
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>MK Dental Admin</h3>
                <button type="button" id="sidebarCollapse" class="btn">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="sidebar-user">
                <div class="user-img">
                    <img src="{{ asset('images/user-placeholder.png') }}" alt="Profile" class="avatar">
                </div>
                <div class="user-info">
                    <h6>{{ Auth::user()->name }}</h6>
                    <span>{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>

                @can('branches_access')
                    <li class="{{ request()->routeIs('admin.branches*') ? 'active' : '' }}">
                        <a href="{{ route('admin.branches.index') }}">
                            <i class="fas fa-hospital"></i> Branches
                        </a>
                    </li>
                @endcan

                @can('services_access')
                    <li class="{{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                        <a href="{{ route('admin.services.index') }}">
                            <i class="fas fa-tooth"></i> Services
                        </a>
                    </li>
                @endcan

                @can('doctors_access')
                    <li class="{{ request()->routeIs('admin.doctors*') ? 'active' : '' }}">
                        <a href="{{ route('admin.doctors.index') }}">
                            <i class="fas fa-user-md"></i> Doctors
                        </a>
                    </li>
                @endcan

                @can('appointments_access')
                    <li class="{{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                        <a href="#appointmentSubmenu" data-bs-toggle="collapse"
                            aria-expanded="{{ request()->routeIs('admin.appointments*') ? 'true' : 'false' }}"
                            class="dropdown-toggle">
                            <i class="fas fa-calendar-check"></i> Appointments
                        </a>
                        <ul class="collapse list-unstyled {{ request()->routeIs('admin.appointments*') ? 'show' : '' }}"
                            id="appointmentSubmenu">
                            <li class="{{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.appointments.index') }}">All Appointments</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.appointments.calendar') ? 'active' : '' }}">
                                <a href="{{ route('admin.appointments.calendar') }}">Calendar View</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('prescriptions_access')
                    <li class="{{ request()->routeIs('admin.prescriptions*') ? 'active' : '' }}">
                        <a href="{{ route('admin.prescriptions.index') }}">
                            <i class="fas fa-file-medical"></i> Prescriptions
                        </a>
                    </li>
                @endcan

                @can('pages_access')
                    <li class="{{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pages.index') }}">
                            <i class="fas fa-file-alt"></i> Pages
                        </a>
                    </li>
                @endcan

                @can('testimonials_access')
                    <li class="{{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
                        <a href="{{ route('admin.testimonials.index') }}">
                            <i class="fas fa-quote-left"></i> Testimonials
                        </a>
                    </li>
                @endcan

                @can('settings_access')
                    <li class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                @endcan

                @can('users_access')
                    <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                @endcan
            </ul>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div id="content" class="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <button type="button" id="sidebarCollapseBtn" class="btn d-md-none">
                            <i class="fa fa-bars"></i>
                        </button>
                        <h4 class="mb-0 ms-2">@yield('title')</h4>
                    </div>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('images/user-placeholder.png') }}" alt="Profile" class="avatar-sm">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Content Container -->
            <div class="container-fluid py-4">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center mb-0">
                                &copy; {{ date('Y') }} Misri Khan Dental Clinic. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Toggle sidebar on mobile
            $('#sidebarCollapse, #sidebarCollapseBtn').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>
