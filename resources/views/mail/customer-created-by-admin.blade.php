<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

Your account has been created by our {{ config('app.name') }} team on {{ Carbon\Carbon::parse($customer->created_at)->format('M d Y') }}, and you're ready to start using our service. Logging into your account on our website: <a href="{{ route('app.auth.login') }}">{{ route('app.auth.login') }}</a>.

Account Creadential:
<div>Email: <span style="background: #F1F1F1;">{{ $customer->email }}</span></div>
<div>Password: <span style="background: #F1F1F1;">{{ $password }}</span></div>
<br>
Thanks for joining {{ config('app.name') }}. We hope you enjoy using our service!

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>