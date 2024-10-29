<x-mail::message>
<strong>Dear {{ $customer_name }},</strong>

We regret to inform you that your account has been deleted by our {{ config('app.name') }} team. If you have any questions or believe this was a mistake, please contact our support team.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>