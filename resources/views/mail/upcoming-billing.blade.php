<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

This is a reminder that your subscription to {{ $subscription->name }} will be billed on {{ Carbon\Carbon::parse($subscription->next_billing_date)->format('M d Y') }}.

Thank you for choosing {{ config('app.name') }}, and we look forward to serving you.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>