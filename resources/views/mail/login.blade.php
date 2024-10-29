<x-mail::message>
<strong>Dear {{ $user->fullname }},</strong>

This is inform you that your have successfully logged into the {{ config('app.name') }} on {{ now()->format('M d Y') }} at {{ now()->format('h:i:s A') }}.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>