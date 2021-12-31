@component('mail::message')

# Hi, {{$details->name}}<br>

A {{$details->type}} transaction of &#8358;{{ number_format($details->amount, 2) }} occured on your  account. Find below transaction details.

<strong>DETAILS</strong><br>

<strong>Type:</strong> {{$details->type}} <br>
<strong>Service:</strong> {{$details->service}} <br>
<strong>Amount:</strong> &#8358;{{number_format($details->amount, 2)}} <br>
<strong>Transaction ID:</strong> {{$details->reference}} <br>
<strong>Transaction Status:</strong> {{$details->status}} <br>

<p>Thank you for patronizing us.</p>

@endcomponent