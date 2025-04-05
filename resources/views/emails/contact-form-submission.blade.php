{{-- resources/views/emails/contact-form-submission.blade.php --}}
@component('mail::message')
    # New Contact Form Submission

    **From:** {{ $contactData['name'] }}
    **Email:** {{ $contactData['email'] }}
    **Subject:** {{ $contactData['subject'] }}

    ## Message:
    {{ $contactData['message'] }}

    @component('mail::button', ['url' => config('app.url')])
        Visit Website
    @endcomponent

    Thank you,<br>
    {{ config('app.name') }}
@endcomponent
