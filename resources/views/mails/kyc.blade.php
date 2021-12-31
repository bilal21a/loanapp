@component('mail::message')

    # Hi, {{$details['profile']['first_name']}} <br>

    <p>Your KYC {{$details['kyc']['type']}}  was {{$details['status']}}.</p>

    <p>
        @if($details['status'] === 'approved')
            {{$details['kyc']['reason_for_approval']}}
        @else
            {{$details['kyc']['reason_for_disapproval']}}
        @endif
    </p>

    For complaints, contact the support team.

    Regards.<br>
@endcomponent
