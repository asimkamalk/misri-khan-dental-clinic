{{-- resources/views/emails/prescriptions/prescription.blade.php --}}
@component('mail::message')
    # Your Medical Prescription

    Dear {{ $prescription->patient_name }},

    Please find attached your medical prescription from **{{ $clinicName }}**. This document contains important
    information about your diagnosis, treatment, and any medications that have been prescribed by Dr. {{ $doctorName }}.

    @component('mail::panel')
        **Prescription Details:**

        **Date:** {{ $prescription->created_at->format('F d, Y') }}
        **Doctor:** Dr. {{ $doctorName }}
        **Branch:** {{ $branchName }}
    @endcomponent

    ## Important Notes:

    - Please show this prescription when purchasing your medications.
    - Follow the medication instructions carefully as prescribed.
    - Complete the full course of treatment even if symptoms improve before completion.
    - If you experience any unexpected side effects, please contact us immediately.

    @if ($prescription->followup_date)
        **Follow-up Appointment:** You are scheduled for a follow-up on
        {{ \Carbon\Carbon::parse($prescription->followup_date)->format('F d, Y') }}.

        @component('mail::button', ['url' => route('appointment.create')])
            Book Follow-up Appointment
        @endcomponent
    @endif

    If you have any questions regarding your prescription or treatment, please don't hesitate to contact us.

    Thank you for choosing {{ $clinicName }} for your dental care.

    Regards,<br>
    Dr. {{ $doctorName }}<br>
    {{ $clinicName }}

    ---
    **Branch Contact:** {{ $branchPhone }}
@endcomponent
