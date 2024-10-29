<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

This is to inform you that a new payment method has been added to your account by our {{ config('app.name') }} team. 

If you have any questions or need further assistance, please contact our support team.

Thank you for your understanding.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>