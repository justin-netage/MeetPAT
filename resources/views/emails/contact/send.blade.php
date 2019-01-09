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

<img src="http://demo.meetpat.co.za/wp-content/uploads/site-logo.png" alt="MeetPAT Logo" />

@endcomponent
