@component('mail::message')

# Hi, {{$data->user->first_name}} <br>

A withdrawal transaction of <strong>{{number_format($data->amount, 2)}}</strong> occured on your  account today. 

Thank you for chosing Mavunifs.

Regards,<br>
     <br>
@endcomponent