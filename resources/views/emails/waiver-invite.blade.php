@component('mail::message')
# Hi {{ $waiverSend->client->name }},

You have been invited to review and sign a waiver.

**{{ $waiverSend->waiver->title }}**

Please click the button below to view and sign it:

@component('mail::button', ['url' => route('waiver.sign', $waiverSend->token)])
View & Sign Waiver
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent