
@component('mail::message')
# Dear {{ $admission->full_name }},

Congratulations! Your admission application has been approved. You are now eligible to create your account on the student portal.

**Your Student ID:** {{ $studentID }}

@component('mail::button', ['url' => 'http://127.0.0.1:8000/auth/registration'])
Create Portal Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent