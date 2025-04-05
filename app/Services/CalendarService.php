<?php
// app/Services/CalendarService.php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

class CalendarService
{
    /**
     * Generate iCal content for an appointment.
     *
     * @param int $appointmentId
     * @return string
     */
    public function generateICalForAppointment(int $appointmentId): string
    {
        $appointment = Appointment::with(['branch', 'doctor'])->findOrFail($appointmentId);

        // Create the event
        $event = $this->createEventFromAppointment($appointment);

        // Create a calendar and add the event
        $calendar = new Calendar([$event]);

        // Convert the calendar to iCal format
        $factory = new CalendarFactory();
        $iCal = $factory->createCalendar($calendar);

        return $iCal;
    }

    /**
     * Generate and save iCal file for an appointment.
     *
     * @param int $appointmentId
     * @return string|null File path or null on failure
     */
    public function saveICalForAppointment(int $appointmentId): ?string
    {
        try {
            $iCalContent = $this->generateICalForAppointment($appointmentId);

            $appointment = Appointment::findOrFail($appointmentId);
            $filename = 'appointment_' . $appointmentId . '.ics';
            $path = 'calendars/' . $filename;

            Storage::disk('public')->put($path, $iCalContent);

            return $path;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Create an iCal event from an appointment.
     *
     * @param Appointment $appointment
     * @return Event
     */
    private function createEventFromAppointment(Appointment $appointment): Event
    {
        // Calculate start and end dates
        $startDate = Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time);
        $endDate = (clone $startDate)->addMinutes(30); // Assuming 30-minute appointments

        // Create the event
        $event = new Event(
            new UniqueIdentifier('appointment-' . $appointment->id . '@' . config('app.url'))
        );

        // Set the time span
        $event->setOccurrence(
            new TimeSpan(
                new DateTime($startDate, false),
                new DateTime($endDate, false)
            )
        );

        // Set the location
        if ($appointment->branch) {
            $event->setLocation(
                new Location($appointment->branch->name . ' - ' . $appointment->branch->address)
            );
        }

        // Set the summary and description
        $doctorName = $appointment->doctor ? 'Dr. ' . $appointment->doctor->name : 'Any Available Doctor';

        $summary = 'Dental Appointment at ' . getSetting('site_title', 'Misri Khan Dental Clinic');
        $description = "Appointment Details:\n";
        $description .= "Patient: " . $appointment->patient_name . "\n";
        $description .= "Date: " . $appointment->appointment_date->format('F d, Y') . "\n";
        $description .= "Time: " . Carbon::parse($appointment->appointment_time)->format('h:i A') . "\n";
        $description .= "Branch: " . ($appointment->branch ? $appointment->branch->name : 'N/A') . "\n";
        $description .= "Doctor: " . $doctorName . "\n";
        $description .= "Status: " . ucfirst($appointment->status) . "\n\n";

        if ($appointment->notes) {
            $description .= "Notes: " . $appointment->notes . "\n\n";
        }

        $description .= "Please arrive 15 minutes before your scheduled appointment time.";

        $event->setSummary($summary);
        $event->setDescription($description);

        return $event;
    }
}