<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

Your subscription with {{ config('app.name') }} has been cancelled as of {{ Carbon\Carbon::parse($subscription->ended_at)->format('M d Y') }}.

Thank you for being with {{ config('app.name') }}. We hope to see you again soon.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>