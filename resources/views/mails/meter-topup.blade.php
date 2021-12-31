@component('mail::message')

# Hi, {{$details->name}}<br>

Your meter topup is {{ $details->status }}. Find below your transaction details.

<strong>DETAILS</strong><br>

<strong>Product:</strong> {{$details->product_name}} <br>
<strong>Meter Number:</strong> {{$details->meter_no}} <br>
<strong>Meter Type:</strong> {{$details->type}} <br>
<strong>Topup Amount:</strong> &#8358;{{number_format($details->amount, 2)}} <br>
<strong>Recharge Token:</strong> {{$details->token}} <br>
<strong>Transaction ID:</strong> {{$details->transId}} <br>
<strong>Transaction Status:</strong> {{$details->status}} <br>

<p>Thank you for patronizing us.</p>

@endcomponent