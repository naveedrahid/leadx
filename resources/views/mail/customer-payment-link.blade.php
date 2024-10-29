<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

We hope this message finds you well.

Thank you for choosing our services. As requested, we have generated a payment link for the selected package. Please find the details below:

<strong>Package Name:</strong> <span>{{ $package->title }}</span><br>
<strong>Amount:</strong> <span>{{ price_format($package->price) }}</span><br>

Please click the link below to complete your payment:

<strong>Payment Link:</strong> <a href="{{ route('app.customer.subscription.payment', [$paymentlink->uuid]) }}">Click Here</a><br>

If you have any questions or need further assistance, feel free to reach out to us.

Thank you for choosing us!

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>