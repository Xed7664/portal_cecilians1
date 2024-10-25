@component('mail::message')
# Dear {{ $admission->full_name }},

Thank you for submitting your admission application. Your application is currently under review.

Here is your admission tracking code:

**Tracking Code:** {{ $tracker_code }}

@component('mail::button', ['url' => route('admission.tracker')])
Track Your Application
@endcomponent

You can use this code to track the status of your admission application.

We will notify you once your application is processed.

Thanks,  
{{ config('app.name') }}
@endcomponent
