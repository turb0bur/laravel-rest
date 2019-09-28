Hello {{$user->name}}!
You have just changed your email address, so we need to verify it. Please, use the link bellow:
{{route('verify', $user->verification_token)}}