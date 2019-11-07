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

<a href="https://dashboard.meetpat.co.za/login"><img width='300' height='auto' src='https://s3.amazonaws.com/meetpat/public/images/site-logo.png' /></a>

@endcomponent
