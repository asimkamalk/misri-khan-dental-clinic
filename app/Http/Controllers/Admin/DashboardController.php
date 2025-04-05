<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Prescription;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $appointmentService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AppointmentService  $appointmentService
     * @return void
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->middleware('auth');
        $this->appointmentService = $appointmentService;
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get counts for dashboard widgets
        $stats = [
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', Carbon::today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'total_branches' => Branch::count(),
            'total_doctors' => Doctor::count(),
            'total_services' => Service::count(),
            'total_prescriptions' => Prescription::count(),
        ];

        // Get recent appointments
        $recentAppointments = Appointment::with(['branch', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get upcoming appointments for today and tomorrow
        $upcomingAppointments = $this->appointmentService->getUpcomingAppointments(5);

        // Get monthly appointment data for chart
        $monthlyAppointments = $this->getMonthlyAppointmentStats();

        // Get appointment status distribution for chart
        $appointmentStatuses = $this->getAppointmentStatusStats();

        return view('admin.dashboard', compact(
            'stats',
            'recentAppointments',
            'upcomingAppointments',
            'monthlyAppointments',
            'appointmentStatuses'
        ));
    }

    /**
     * Get monthly appointment statistics for the chart.
     *
     * @return array
     */
    private function getMonthlyAppointmentStats()
    {
        $currentYear = Carbon::now()->year;

        $monthlyData = Appointment::select(
            DB::raw('MONTH(appointment_date) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('appointment_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $data = [];

        // Fill in data for all months
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('F');
            $data[] = $monthlyData[$i] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get appointment status distribution for the chart.
     *
     * @return array
     */
    private function getAppointmentStatusStats()
    {
        $statuses = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->toArray();

        $labels = [];
        $data = [];
        $colors = [
            'pending' => '#f6c23e',
            'confirmed' => '#4e73df',
            'completed' => '#1cc88a',
            'cancelled' => '#e74a3b'
        ];
        $backgroundColors = [];

        foreach ($statuses as $status) {
            $labels[] = ucfirst($status['status']);
            $data[] = $status['count'];
            $backgroundColors[] = $colors[$status['status']] ?? '#6c757d';
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'backgroundColors' => $backgroundColors
        ];
    }
}