@component('mail::message')

# Hi, {{$data->user->first_name}} <br>

Your loan payment is due, the system was unable to charge your account. Kindly login to your app dashboard and 
use the Pay option under loans to make payment manually or use the button below.

<strong>Details</strong><br>

<strong>Loan amount:</strong> ZAR{{number_format($data->loan->amount, 2)}} <br>
<strong>Amount due:</strong> ZAR{{number_format($data->loan->amount + $data->loan->interest, 2)}} <br>
<strong>Bank/Gateway response:</strong> {{$data->gateway_response}} <br>

Regards,<br>
    .<br>
@endcomponent