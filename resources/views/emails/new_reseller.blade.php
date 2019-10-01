@component('mail::message')
# MeetPAT Reseller Registration

@component('mail::panel')

<h2>Welcome to MeetPAT!</h2>
<p>Below are your login details</p>
@endcomponent

<br>
<b>E-Mail Address</b>: {{$email}}

<br>
<b>Password</b>: {{$password}}

---

<br>

Thanks,<br>
{{ config('app.name') }}

<img src="http://demo.meetpat.co.za/wp-content/uploads/site-logo.png" alt="MeetPAT Logo" />

@endcomponent
