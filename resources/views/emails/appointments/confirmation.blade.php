{{-- resources/views/emails/appointments/confirmation.blade.php --}}
@component('mail::message')
    # Appointment Confirmation

    Dear {{ $appointment->patient_name }},

    @if ($isAdminCopy)
        A new appointment has been booked at {{ $clinicName }}.
    @else
        Thank you for booking an appointment with {{ $clinicName }}. Your appointment details are as follows:
    @endif

    @component('mail::panel')
        **Appointment Details:**

        **Date:** {{ $appointment->appointment_date->format('l, F d, Y') }}
        **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
        **Branch:** {{ $appointment->branch->name }}
        **Address:** {{ $appointment->branch->address }}
        @if ($appointment->doctor)
            **Doctor:** Dr. {{ $appointment->doctor->name }}
            **Specialization:** {{ $appointment->doctor->specialization }}
        @endif

        **Status:** {{ ucfirst($appointment->status) }}
    @endcomponent

    @if (!$isAdminCopy)
        ## What's Next?

        1. Our staff will contact you to confirm your appointment.
        2. Please arrive 15 minutes before your scheduled time.
        3. Bring any previous dental records or x-rays if available.
        4. If you need to reschedule or cancel, please contact us at least 24 hours in advance.

        @component('mail::button', ['url' => route('contact')])
            Contact Us
        @endcomponent
    @endif

    @if ($appointment->status == 'pending')
        Please note that your appointment is currently **pending** and will be confirmed by our staff.
    @endif

    @if ($isAdminCopy)
        **Patient Contact Information:**
        - **Name:** {{ $appointment->patient_name }}
        - **Email:** {{ $appointment->patient_email }}
        - **Phone:** {{ $appointment->patient_phone }}

        @if ($appointment->notes)
            **Notes:**
            {{ $appointment->notes }}
        @endif
    @endif

    Thank you,<br>
    {{ $clinicName }}

    @if (!$isAdminCopy)
        ---
        If you have any questions, please contact us at {{ $clinicPhone }} or {{ $clinicEmail }}.
    @endif
@endcomponent

@if (!$isAdminCopy)
    ## Add to Calendar

    We've attached a calendar file (.ics) to this email. You can click this file to add the appointment to your calendar
    application.

    You can also use the links below to add this appointment to your preferred calendar:

    @component('mail::button', ['url' => $icalUrl, 'color' => 'success'])
        Download Calendar File
    @endcomponent

    @component('mail::table')
        | Calendar Service | Link |
        |:----------------|:------|
        | Google Calendar | [Add to Google
        Calendar](https://calendar.google.com/calendar/render?action=TEMPLATE&text=Dental+Appointment+at+{{ urlencode($clinicName) }}&dates={{ $appointment->appointment_date->format('Ymd') }}T{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('His') }}/{{ $appointment->appointment_date->format('Ymd') }}T{{ \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes(30)->format('His') }}&details={{ urlencode('Appointment at ' . $clinicName) }}&location={{ urlencode($appointment->branch->address ?? '') }}&sf=true&output=xml)
        |
        | Outlook Calendar | [Add to Outlook]({{ $icalUrl }}) |
        | Yahoo Calendar | [Add to
        Yahoo](https://calendar.yahoo.com/?v=60&VIEW=d&TITLE=Dental+Appointment+at+{{ urlencode($clinicName) }}&ST={{ $appointment->appointment_date->format('Ymd') }}T{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('His') }}&DUR=0030&DESC={{ urlencode('Appointment at ' . $clinicName) }}&in_loc={{ urlencode($appointment->branch->address ?? '') }})
        |
    @endcomponent
@endif
