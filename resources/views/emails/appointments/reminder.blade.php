{{-- resources/views/emails/appointments/reminder.blade.php --}}
@component('mail::message')
    # Appointment Reminder

    Dear {{ $appointment->patient_name }},

    This is a friendly reminder that you have a dental appointment scheduled for tomorrow at {{ $clinicName }}.

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

    ## Appointment Preparation:

    - Please arrive 15 minutes before your scheduled appointment time to complete any necessary paperwork.
    - Bring your ID and any previous dental records, if available.
    - If you're taking any medications, please bring a list with you.
    - If you have dental insurance, bring your insurance card.

    @if ($appointment->status == 'pending')
        **Note:** Your appointment is currently in **pending** status. We will confirm your appointment shortly. If you have
        not received a confirmation by the end of the day, please contact us to verify your appointment.
    @endif

    @component('mail::button', ['url' => route('contact')])
        Contact Us
    @endcomponent

    If you need to reschedule or cancel your appointment, please let us know as soon as possible so we can accommodate other
    patients.

    Thank you,<br>
    {{ $clinicName }}

    ---
    If you have any questions, please contact us at {{ $clinicPhone }} or {{ $clinicEmail }}.
@endcomponent
