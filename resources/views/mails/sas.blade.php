@component('mail::message')

# Hi, {{$details['first_name']}} <br>
@if($details['type'] === 'submission')
<p>Your social media profiles submission was successful, however, its pending agent and admin approval.
We will notify you when this approval is done.
</p>
@else
@if($details['status'] === 'approved')
<p>This is to notify you that your social media profile has been approved. </p>
@else
<p>This is to notify you that your social media profile was declined. </p>
@endif
@endif
<p>Thank you for choosing Mavunif.</p>
Regards,<br>
MAVUNIF Team.<br>
@endcomponent
