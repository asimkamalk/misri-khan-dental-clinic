{{-- resources/views/emails/appointments/status-update.blade.php --}}
@component('mail::message')
    # Appointment Status Update

    Dear {{ $appointment->patient_name }},

    We're writing to inform you that the status of your dental appointment has been updated from
    **{{ ucfirst($oldStatus) }}** to **{{ ucfirst($appointment->status) }}**.

    @component('mail::panel')
        **Appointment Details:**

        **Date:** {{ $appointment->appointment_date->format('l, F d, Y') }}
        **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
        **Branch:** {{ $appointment->branch->name }}
        **Address:** {{ $appointment->branch->address }}
        @if ($appointment->doctor)
            **Doctor:** Dr. {{ $appointment->doctor->name }}
        @endif

        **Status:** {{ ucfirst($appointment->status) }}
    @endcomponent

    @if ($appointment->status == 'confirmed')
        Your appointment has been **confirmed**. Please arrive 15 minutes before your scheduled time and bring any relevant
        dental records with you.
    @elseif($appointment->status == 'completed')
        Thank you for visiting {{ $clinicName }}. Your appointment has been marked as **completed**. If you have any
        follow-up questions about your treatment, please don't hesitate to contact us.
    @elseif($appointment->status == 'cancelled')
        Your appointment has been **cancelled**. If you did not request this cancellation, or if you would like to
        reschedule, please contact us as soon as possible.

        @component('mail::button', ['url' => route('appointment.create')])
            Book New Appointment
        @endcomponent
    @endif

    @component('mail::button', ['url' => route('contact')])
        Contact Us
    @endcomponent

    Thank you,<br>
    {{ $clinicName }}

    ---
    If you have any questions, please contact us at {{ $clinicPhone }} or {{ $clinicEmail }}.
@endcomponent
