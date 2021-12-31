@component('mail::message')

# Hi, {{$result->name}} <br>

Your AutoSave on your  account today was unsuccessful. You can login to your app dashboard and 
use the Quick Save option to save manually.

<strong>Details</strong><br>

<strong>Status:</strong> {{$result->status}} <br>
<strong>Amount:</strong> {{number_format($result->amount, 2)}} <br>
<strong>Bank/Gateway response:</strong> {{$result->response}} <br>


Regards,<br>
  <br>
@endcomponent