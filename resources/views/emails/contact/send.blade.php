@component('mail::message')
# MeetPAT Online Query

@component('mail::panel')
## A vistor has sent an online query to MeetPAT !
@endcomponent


<br>
<b>Name</b>: {{$name}}

---

<br>
<b>Email</b>: {{$email}}

---

<br>
<b>Query</b>

{{$message}}

Thanks,<br>
{{ config('app.name') }}

<a href="https://dashboard.meetpat.co.za/login"><img width='300' height='auto' src='https://s3.amazonaws.com/meetpat/public/images/site-logo.png' /></a>

@endcomponent
