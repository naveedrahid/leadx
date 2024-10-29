<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

We regret to inform you that your subscription has been cancelled by our {{ config('app.name') }} team as of {{ Carbon\Carbon::parse($subscription->ended_at)->format('M d Y') }}. If this was a mistake or if you have any questions, please contact our support team.

Thank you for your understanding, and we hope to serve you again in the future.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>