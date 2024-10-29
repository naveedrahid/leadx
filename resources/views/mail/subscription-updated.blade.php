<x-mail::message>
<strong>Dear {{ $customer->fullname }},</strong>

We wanted to let you know that your subscription with {{ config('app.name') }} has been successfully updated. Below are the details of your subscription:

<strong>Plan Name:</strong> <span>{{ $subscription->name }}</span><br>
<strong>Subscription Start Date:</strong> <span>{{ Carbon\Carbon::parse($subscription->start_at)->format('M d Y') }}</span><br>
@if(!$package->free_plan)
    @if($subscription->next_billing_date)
        <strong>Next Billing Date:</strong> <span>{{ Carbon\Carbon::parse($subscription->next_billing_date)->format('M d Y') }}</span><br>
    @endif
@endif
<strong>Status:</strong> <span>{{ $subscription->status }}</span><br>
@php
    $price = $subscription->amount;
@endphp
@if($coupon != null) 
    <strong>Discount:</strong> <span>{{ $coupon->discount }}</span><br>

    @php
        $price = discount_price($price, $coupon->amount, $coupon->type);
    @endphp
@endif
@if($subscription->status == 'trialing')
    <strong>Free Trial:</strong> <span>{{ $subscription->package->trial_period_days }} {{ $subscription->package->trial_period_days > 1 ? 'Days' : 'Day' }}</span><br>
    
    @php
        $price = 0;
    @endphp
@endif
<strong>New Amount Charged:</strong> <span>{{ price_format($price) }}</span><br>

Thank you for choosing {{ config('app.name') }}. We appreciate having you as a valued customer.

<br>
Best regards,<br>
{{ config('app.name') }} Team
<br><br><hr>
<small>If you have any questions or need assistance, feel free to reply to this email or contact our support team at <a href="mailto:{{ config('app.support.address') }}">{{ config('app.support.address') }}</a>. You can also reach us by phone at <a href="tel:{{ config('app.support.phone_number') }}">{{ config('app.support.phone_number') }}</a>. We're here to help!</small>
</x-mail::message>