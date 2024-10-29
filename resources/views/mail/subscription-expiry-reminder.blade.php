<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

Your subscription to {{ $subscription->name }} is set to expire on {{ Carbon\Carbon::parse($subscription->ended_at)->format('M d Y') }}.

If you'd like to renew, please visit our website or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a> for assistance.

Thank you for being with us, and we hope to continue serving you!

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>