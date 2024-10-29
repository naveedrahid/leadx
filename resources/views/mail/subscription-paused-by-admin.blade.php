<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

We wanted to let you know that your subscription has been paused by our {{ config('app.name') }} as of {{ Carbon\Carbon::parse($subscription->paused_at)->format('M d Y') }}. This means you won't be charged during the pause, and access to our services will be limited during this time.

If you would like to resume your subscription, please contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>.

Thank you for choosing {{ config('app.name') }}. We look forward to serving you when you're ready to resume.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>