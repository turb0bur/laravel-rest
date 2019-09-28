@component('mail::message')
# Hello {{$user->name}}!

Thank you for creation an account. Please verify your email using the button

@component('mail::button', ['url' => route('verify', $user->verification_token)])
    Verify user
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent