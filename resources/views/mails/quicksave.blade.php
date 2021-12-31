@component('mail::message')

# Hi, {{$data->user->first_name}} <br>

Your Quick Save of <strong>{{number_format($data->amount, 2)}}</strong> on your  wallet today was successful. 

Thank you for chosing .


Regards,<br>
    .<br>
@endcomponent