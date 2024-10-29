<x-mail::message>
<strong>Dear {{ $user->fullname }},</strong>

Welcome to {{ config('app.name') }}! Thanks for signing up. Your account is set up, and you're ready to start using our service.

Account Creadential:
<div>Email: <span style="background: #F1F1F1;">{{ $user->email }}</span></div>
<div>Password: <span style="background: #F1F1F1;">{{ $password }}</span></div>
<br>
Thanks again for joining {{ config('app.name') }}. We hope you enjoy using our service!

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>