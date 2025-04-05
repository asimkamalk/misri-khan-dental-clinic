<?php
// app/Services/AppointmentService.php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentService
{
    /**
     * Get available time slots for a specific date and branch.
     *
     * @param string $date
     * @param int $branchId
     * @param int|null $doctorId
     * @return array
     */
    public function getAvailableTimeSlots(string $date, int $branchId, ?int $doctorId = null): array
    {
        $branch = Branch::findOrFail($branchId);

        // Convert to Carbon instances
        $date = Carbon::parse($date)->startOfDay();
        $openingTime = Carbon::parse($branch->opening_time);
        $closingTime = Carbon::parse($branch->closing_time);

        // Set the opening and closing times for the selected date
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $openingTime->format('H:i:s'));
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $closingTime->format('H:i:s'));

        // Generate time slots (30 minutes intervals)
        $slots = [];
        $current = clone $startTime;

        while ($current < $endTime) {
            $slots[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        // Get booked appointments for the selected date, branch, and doctor (if provided)
        $query = Appointment::where('appointment_date', $date->format('Y-m-d'))
            ->where('branch_id', $branchId)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $bookedSlots = $query->pluck('appointment_time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // Filter out booked slots
        $availableSlots = array_diff($slots, $bookedSlots);

        return $availableSlots;
    }

    /**
     * Check if a time slot is available.
     *
     * @param string $date
     * @param string $time
     * @param int $branchId
     * @param int|null $doctorId
     * @return bool
     */
    public function isTimeSlotAvailable(string $date, string $time, int $branchId, ?int $doctorId = null): bool
    {
        // Convert the time to a standardized format
        $formattedTime = Carbon::parse($time)->format('H:i');

        // Get available slots
        $availableSlots = $this->getAvailableTimeSlots($date, $branchId, $doctorId);

        return in_array($formattedTime, $availableSlots);
    }

    /**
     * Get upcoming appointments.
     *
     * @param int $limit
     * @param int|null $branchId
     * @param int|null $doctorId
     * @return Collection
     */
    public function getUpcomingAppointments(int $limit = 10, ?int $branchId = null, ?int $doctorId = null): Collection
    {
        $query = Appointment::with(['branch', 'doctor'])
            ->where('appointment_date', '>=', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get today's appointments.
     *
     * @param int|null $branchId
     * @param int|null $doctorId
     * @return Collection
     */
    public function getTodayAppointments(?int $branchId = null, ?int $doctorId = null): Collection
    {
        $query = Appointment::with(['branch', 'doctor'])
            ->where('appointment_date', Carbon::today())
            ->orderBy('appointment_time');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->get();
    }

    /**
     * Get appointments for a specific date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int|null $branchId
     * @param int|null $doctorId
     * @return Collection
     */
    public function getAppointmentsByDateRange(
        string $startDate,
        string $endDate,
        ?int $branchId = null,
        ?int $doctorId = null
    ): Collection {
        $query = Appointment::with(['branch', 'doctor'])
            ->whereBetween('appointment_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        return $query->get();
    }

    /**
     * Update appointment status.
     *
     * @param int $appointmentId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $appointmentId, string $status): bool
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->status = $status;

        return $appointment->save();
    }
}