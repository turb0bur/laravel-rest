@component('mail::message')
# Hello {{$user->name}}!

You have just changed your email address, so we need to verify it. Please, use the button bellow:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
    Verify user
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent