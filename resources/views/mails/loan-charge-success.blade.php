@component('mail::message')

# Hi, {{$data->user->first_name}} <br>

Your loan repayment of {{number_format($data->loan->amount + $data->loan->interest, 2)}} was successful. Find details below.

<strong>Details</strong><br>

<strong>Request date:</strong> {{$data->loan->request_date}} <br>
<strong>Due date:</strong> {{$data->loan->due_date}} <br>
<strong>Loan amount:</strong> {{number_format($data->loan->amount, 2)}} <br>
<strong>Amount due:</strong> {{number_format($data->loan->amount + $data->loan->interest, 2)}} <br>

Regards,<br>
    .<br>
@endcomponent