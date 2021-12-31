@component('mail::message')

# Hi, {{$result->name}} <br>

Your AutoSave on your  account today was successful. You can find details below.

<strong>Details</strong><br>

<strong>Status:</strong> {{$result->status}} <br>
<strong>Amount:</strong> {{number_format($result->amount, 2)}} <br>

Regards,<br>
     <br>
@endcomponent