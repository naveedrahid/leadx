<template>
    <Head title="Billing" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Billing</template>
                <li class="breadcrumb-item"><Link :href="route('app.customer.subscription.index')" class="text-dark">Subscription</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Billing</li>
            </Breadcrumb>
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.subscription.index')" class="btn btn-primary btn-sm">All Subscriptions</Link>
                    <Link :href="route('app.customer.subscription.billing.history')" class="btn btn-primary btn-sm">Billing History</Link>
                    <Link :href="route('app.customer.subscription.license')" class="btn btn-primary btn-sm">Plugin License</Link>
                </div>
            </div>
            <template v-if="Object.keys(current_subscription).length>0">
                <template v-if="current_subscription.status == 'active'">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center font-medium me-3 me-md-0">
                            <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                            <span class="fw-bold">Your subscription is active. Thank you for being with us!</span>
                        </div>
                    </div>
                </template>
                <template v-if="current_subscription.status == 'trialing'">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center font-medium me-3 me-md-0">
                            <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                            <span class="fw-bold">Your subscription is currently in the trial period. Enjoy our services to the fullest!</span>
                        </div>
                    </div>
                </template>
                <template v-if="current_subscription.status == 'paused'">
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex align-items-center font-medium me-3 me-md-0">
                            <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                            <span class="fw-bold">Your subscription is currently paused. Please resume your subscription to continue enjoying our services. You may also upgrade your plan.</span>
                        </div>
                    </div>
                </template>
                <template v-if="current_subscription.status == 'past_due'">
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex align-items-center font-medium me-3 me-md-0">
                            <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                            <span class="fw-bold">Your subscription payment is past due. Please change your payment method to settle the outstanding balance and maintain your subscription. You may also upgrade your plan.</span>
                        </div>
                    </div>
                </template>
                <template v-if="current_subscription.status == 'unpaid'">
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex align-items-center font-medium me-3 me-md-0">
                            <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                            <span class="fw-bold">Your subscription payment is overdue. Please change your payment method to avoid service disruption. You may also upgrade your plan.</span>
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                <template v-if="Object.keys(subscription).length>0">
                    <template v-if="subscription.status == 'canceled'">
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-center font-medium me-3 me-md-0">
                                <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                                <span class="fw-bold">Your subscription has been canceled. We're sorry to see you go. If you have any feedback or wish to rejoin, please contact us. You may also start a new subscription process.</span>
                            </div>
                        </div>
                    </template>
                    <template v-if="subscription.status == 'incomplete'">
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-center font-medium me-3 me-md-0">
                                <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                                <span class="fw-bold">Your subscription process is incomplete. Please start a new subscription process.</span>
                            </div>
                        </div>
                    </template>
                    <template v-if="subscription.status == 'incomplete_expired'">
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-center font-medium me-3 me-md-0">
                                <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                                <span class="fw-bold">Your subscription attempt has expired. Please start a new subscription process.</span>
                            </div>
                        </div>
                    </template>
                </template>
            </template>
            <div class="row">
                <div class="col-md-6">
                    <template v-if="Object.keys(current_subscription).length>0">
                        <template v-if="user.other?.has_exceeded_leads_limit">
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center font-medium me-3 me-md-0">
                                    <i class="ti ti-bell icon-shake fs-7 me-2"></i>
                                    <span class="fw-bold">Sorry, you have exceeded your leads limit.</span>
                                </div>
                            </div>
                        </template>
                        <div class="rounded border border-2 p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                                <h3 class="fs-5 fw-bolder text-muted mb-1">{{ current_subscription.name }}</h3>
                                <div class="border border-2 rounded py-1 px-2 d-flex align-items-center gap-2">
                                    <span style="width: 7px;height: 7px;" class="rounded" :class="{
                                        'bg-success': current_subscription.status == 'active' || current_subscription.status == 'trialing',
                                        'bg-danger': current_subscription.status == 'unpaid' || current_subscription.status == 'past_due',
                                        'bg-warning': current_subscription.status == 'paused'
                                    }"></span>
                                    <span class="fs-2 fw-bold">{{ subsStatuses.hasOwnProperty(current_subscription.status) ? subsStatuses[current_subscription.status] : current_subscription.status }}</span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-muted fs-6 fw-bold">{{ priceFormat(current_subscription.amount) }} {{ $page.props.currency_code }}</div>
                                <div class="mt-2 fs-2 text-dark fw-bold" v-if="current_subscription.coupon != null && current_subscription.coupon_expire_at != null">
                                    <template v-if="checkExpiry(current_subscription.coupon_expire_at)">
                                        Total amount with discount: {{ $page.props.currency_symbol }}{{ discountPrice(current_subscription.amount, current_subscription.coupon.amount, current_subscription.coupon.type) }}
                                    </template>
                                </div>
                            </div>
                            <template v-if="current_subscription.status == 'trialing'">
                                <div class="d-flex flex-column gap-1 mb-6">
                                    <span class="fs-3 fw-bolder">Trial Period</span>
                                    <div class="fs-2 fw-bold">
                                        {{ current_subscription.trial_start_at ? dateFormat(current_subscription.trial_start_at, 'DD.MM.YYYY') : '-' }} to {{ current_subscription.next_billing_date ? dateFormat(current_subscription.next_billing_date, 'DD.MM.YYYY') : '-' }}
                                        <span class="ps-1">({{ current_subscription.package?.trial_period_days }} {{ current_subscription.package?.trial_period_days > 1 ? 'Days' : 'Day' }} Free Trial)</span>
                                    </div>
                                </div>
                            </template>
                            <div class="border p-4 rounded d-flex flex-column gap-2 mb-6">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-2 fw-bolder">Website Limit:</span>
                                    <span class="fs-2 fw-bold">{{ current_subscription.package?.website_limit ? (numFormat(current_subscription.websites.length) +' / '+ numFormat(current_subscription.package?.website_limit)) : 'Unlimited' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2" :class="{
                                    'text-danger': user.other.has_exceeded_leads_limit
                                }">
                                    <span class="fs-2 fw-bolder">Leads Limit:</span>
                                    <span class="fs-2 fw-bold">{{ current_subscription.package?.lead_limit ? (current_subscription.leads +' / '+ current_subscription.package?.lead_limit) : 'Unlimited' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-2 fw-bolder">App Access:</span>
                                    <span class="fs-2 fw-bold">{{ current_subscription.package?.app_access ? 'Yes' : 'No' }}</span>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-1 mb-6">
                                <span class="fs-3 fw-bolder">Started Date</span>
                                <div class="fs-2 fw-bold">{{ current_subscription.start_at ? dateFormat(current_subscription.start_at, 'DD.MM.YYYY') : '-' }}</div>
                            </div>

                            <template v-if="current_subscription.status == 'paused'">
                                <div class="d-flex flex-column gap-1 mb-6">
                                    <span class="fs-3 fw-bolder">Paused Date</span>
                                    <div class="fs-2 fw-bold">{{ current_subscription.paused_at ? dateFormat(current_subscription.paused_at, 'DD.MM.YYYY') : '-' }}</div>
                                </div>
                            </template>

                            <template v-if="user.customer_details?.auto_renewal_subscription">
                                <template v-if="current_subscription.status != 'paused' && !current_subscription.package?.free_plan">
                                    <div class="d-flex flex-column gap-1 mb-6">
                                        <span class="fs-3 fw-bolder">Next Billing Date</span>
                                        <div class="fs-2 fw-bold">{{ current_subscription.next_billing_date ? dateFormat(current_subscription.next_billing_date, 'DD.MM.YYYY') : '-' }}</div>
                                    </div>
                                </template>
                            </template>

                            <template v-if="!user.customer_details?.auto_renewal_subscription || current_subscription.package?.free_plan">
                                <template v-if="current_subscription.status != 'paused'">
                                    <div class="d-flex flex-column gap-1 mb-6">
                                        <span class="fs-3 fw-bolder">Ended Date</span>
                                        <div class="fs-2 fw-bold">{{ current_subscription.ended_at ? dateFormat(current_subscription.ended_at, 'DD.MM.YYYY') : '-' }}</div>
                                    </div>
                                </template>
                            </template>

                            <template v-if="current_subscription.coupon != null && current_subscription.coupon_expire_at != null">
                                <template v-if="!isExpired(current_subscription.coupon_expire_at)">
                                    <div class="d-flex flex-column gap-1 mb-6">
                                        <span class="fs-3 fw-bolder">Discount Coupon</span>
                                        <span class="fs-2 fw-bold">Coupon: {{ current_subscription.coupon.title }}</span>
                                        <div class="fs-2 fw-bold">Discount: {{ current_subscription.coupon.discount }}</div>
                                        <div class="fs-2 fw-bold">Expire at: {{ dateFormat(current_subscription.coupon_expire_at, 'DD.MM.YYYY') }}</div>
                                    </div>
                                </template>
                            </template>

                            <div class="d-flex align-items-center gap-2 mt-5">
                                <button class="btn btn-primary" v-if="current_subscription.status != 'unpaid' && current_subscription.status != 'past_due'" @click.prevent="takePlan(true)">Upgrade Subscription</button>
                                <button class="btn btn-info" v-if="current_subscription.status == 'paused'" @click.prevent="confirmResumeSubscription">Resume Subscription</button>
                                <button class="btn btn-danger" @click.prevent="confirmCancelSubscription">Cancel</button>
                            </div>
                        </div>

                        <template v-if="(current_subscription.status == 'active' || current_subscription.status == 'trialing') && !current_subscription.package?.free_plan && !current_subscription.package?.duration_lifetime">
                            <div class="rounded border border-2 p-4 mb-4">
                                <div class="card-body p-8">
                                    <div class="mb-4">
                                        <div class="fs-5 fw-bolder text-dark mb-1">Discount Code <span class="text-danger">*</span></div>
                                        <div class="fs-3 text-dark">You can apply coupon in your current plan</div>
                                    </div>
                                    <div class="d-flex flex-column gap-3 align-items-start">
                                        <div class="w-100">
                                            <input type="text" v-model="coupon.discount_code" id="payment-discount-code" class="form-control py-3" placeholder="e.g. EX123_TEST_CODE">
                                            <div class="text-danger" v-if="errors.discount_code">
                                                {{ errors.discount_code }}
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary rounded" @click.prevent="applyDiscount">Apply Discount</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                    <template v-else-if="Object.keys(subscription).length>0">
                        <div class="rounded border border-2 p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                                <h3 class="fs-5 fw-bolder text-muted mb-1">{{ subscription.name }}</h3>
                                <div class="border border-2 rounded py-1 px-2 d-flex align-items-center gap-2">
                                    <span style="width: 7px;height: 7px;" class="rounded bg-danger"></span>
                                    <span class="fs-2 fw-bold">{{ subsStatuses.hasOwnProperty(subscription.status) ? subsStatuses[subscription.status] : subscription.status }}</span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="text-muted fs-6 fw-bold">{{ priceFormat(subscription.amount) }} {{ $page.props.currency_code }}</div>
                                <div class="mt-2 fs-2 text-dark fw-bold" v-if="subscription.coupon != null && subscription.coupon_expire_at != null">
                                    <template v-if="checkExpiry(subscription.coupon_expire_at)">
                                        Total amount with discount: {{ $page.props.currency_symbol }}{{ discountPrice(subscription.amount, subscription.coupon.amount, subscription.coupon.type) }}
                                    </template>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1 mb-6">
                                <span class="fs-3 fw-bolder">Started Date</span>
                                <div class="fs-2 fw-bold">{{ subscription.start_at ? dateFormat(subscription.start_at, 'DD.MM.YYYY') : '-' }}</div>
                            </div>
                            <div class="d-flex flex-column gap-1 mb-6">
                                <span class="fs-3 fw-bolder">Ended Date</span>
                                <div class="fs-2 fw-bold">{{ subscription.ended_at ? dateFormat(subscription.ended_at, 'DD.MM.YYYY') : '-' }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-5">
                                <button class="btn btn-primary" @click.prevent="takePlan(false)">Take a Plan</button>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="rounded border border-2 p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                                <h3 class="fs-5 fw-bolder text-muted mb-1">No Subscription</h3>
                                <div class="border border-2 rounded py-1 px-2 d-flex align-items-center gap-2">
                                    <span style="width: 7px;height: 7px;" class="rounded bg-danger"></span>
                                    <span class="fs-2 fw-bold">Pending</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1 mb-6">
                                <span class="fs-3 fw-bolder">Started Date</span>
                                <div class="fs-2 fw-bold">-</div>
                            </div>
                            <div class="d-flex flex-column gap-1 mb-6">
                                <span class="fs-3 fw-bolder">Ended Date</span>
                                <div class="fs-2 fw-bold">-</div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-5">
                                <button class="btn btn-primary" @click.prevent="takePlan(false)">Take a Plan</button>
                            </div>
                        </div>
                    </template>
                    <div class="rounded border border-2 p-4 mb-4" v-if="!current_subscription.package?.free_plan && !current_subscription.package?.duration_lifetime">
                        <h3 class="fs-5 fw-bolder text-muted mb-5">Auto-Renewal Subscription</h3>
                        <div class="d-flex flex-column align-items-start gap-1 mb-6">
                            <span class="fs-3 fw-bold">Status</span>
                            <div class="border border-2 rounded py-2 px-3 d-flex align-items-center gap-2">
                                <span style="width: 7px;height: 7px;" class="rounded" :class="{
                                    'bg-success': user.customer_details?.auto_renewal_subscription,
                                    'bg-danger': !user.customer_details?.auto_renewal_subscription
                                }"></span>
                                <span class="fs-3 fw-bold">{{ user.customer_details?.auto_renewal_subscription ? 'Enabled' : 'Disabled' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-5">
                            <button class="btn" :class="{
                                'btn-danger': user.customer_details?.auto_renewal_subscription,
                                'btn-primary': !user.customer_details?.auto_renewal_subscription
                            }" @click.prevent="confirmAutoRenewalSubscription">{{ user.customer_details?.auto_renewal_subscription ? 'Disabled' : 'Enabled' }} Auto-Renewal</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rounded border border-2 p-4 mb-4" v-if="Object.keys(current_subscription).length>0">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                            <h3 class="fs-5 fw-bolder text-muted mb-1">Websites</h3>
                            <span class="d-block fw-bold fs-2 mb-1 text-end">
                                <span>Website Limit : </span>
                                <span class="text-dark fw-bolder">{{ current_subscription.package?.website_limit ? numFormat(current_subscription.package?.website_limit) : 'Unlimited' }}</span>
                            </span>
                        </div>
                        <div class="d-flex flex-column gap-3 mb-5">
                            <p class="mb-0 fw-bold text-muted fs-3">Current Subscription Websites</p>
                            <template v-if="current_subscription.websites.length>0">
                                <template v-for="website in current_subscription.websites" :key="website.id">
                                    <div class="d-flex align-items-center gap-3 py-2 px-3 border border-2 rounded">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ website.website_name }}</h6>
                                            <span class="fs-2">{{ website.website_url }}</span>
                                        </div>
                                        <div class="ms-auto text-end">
                                            <span class="fs-2 fw-bolder text-capitalize rounded-1 py-2 px-3" :class="{
                                                'bg-light-info text-info': website.status == 'active',
                                                'bg-light-danger text-danger': website.status == 'deactive',
                                            }">{{ website.status }}</span>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template v-else>
                                <span class="fs-2 fw-bolder text-danger">No Website Selected</span>
                            </template>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-info" @click.prevent="openAddNewWebsiteForm">Add New Website</button>
                            <button class="btn btn-primary" @click.prevent="openChangeWebsiteForm">Select Websites</button>
                        </div>
                    </div>
                    <template v-if="Object.keys(default_card).length>0">
                        <div class="rounded border border-2 p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <h3 class="fs-5 fw-bolder text-muted mb-1">Payment Method</h3>
                                <button class="btn btn-primary btn-sm" @click.prevent="addNewCardForm">Add New Card</button>
                            </div>
                            <div class="d-flex flex-column gap-1 mb-5">
                                <span class="fs-4 text-capitalize fw-bold mb-1">{{ default_card.brand }}</span>
                                <div class="fs-2">**** **** **** {{ default_card.last4 }}</div>
                                <div class="fs-2">Card expires at {{ numFormat(default_card.exp_month) }}/{{ default_card.exp_year }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-info" @click.prevent="changePaymentMethod">Change Payment Method</button>
                                <button class="btn btn-danger" @click.prevent="confirmRemovePaymentMethod($event, default_card.id)" v-if="user_cards.length>1">Remove Card</button>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="rounded border border-2 p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <h3 class="fs-5 fw-bolder text-muted mb-1">Payment Method</h3>
                                <button class="btn btn-primary btn-sm" @click.prevent="addNewCardForm">Add New Card</button>
                            </div>
                            <div class="d-flex flex-column gap-1 mb-5">
                                <div class="fs-3">You don't have a default card set. Please add a new card or set a default card to proceed with your subscription.</div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <button class="btn btn-info" @click.prevent="changePaymentMethod" v-if="user_cards.length>0">Select Payment Method</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>

    <div id="payment-method-list" class="modal fade" :class="{
        'show d-block': pm_list_toggle == true
    }" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="fs-5 fw-bolder text-muted mb-0">Payment Methods</h3>
                    <button type="button" class="btn p-0 outline-none border-none" @click.prevent="closePaymentMethodList">
                        <i class="ti ti-x fs-7"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <template v-if="user_cards.length>0">
                        <template v-for="(user_card, index) in user_cards" :key="index">
                            <template v-if="! user_card.is_default">
                                <div class="pmcards-items">
                                    <input type="checkbox" class="d-none" :id="user_card.id+'-payment-method'" :checked="form.change_payment_method.paymentMethodId == user_card.pm_id">
                                    <label class="rounded border border-2 mb-4 p-4 w-100" :class="{
                                        '': form.change_payment_method.paymentMethodId == user_card.pm_id
                                    }" @click.prevent="changePaymentMethodToggle(user_card.pm_id)">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="d-flex flex-column gap-2 w-100">
                                                <h3 class="fs-4 fw-bolder mb-1">{{ user_card.card_holder_name }}</h3>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-1 w-100">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fs-3 text-capitalize fw-bold mb-1">{{ user_card.brand }}</span>
                                                <div class="fs-2">**** **** **** {{ user_card.last4 }}</div>
                                            </div>
                                            <div class="fs-2">Card expires at {{ numFormat(user_card.exp_month) }}/{{ user_card.exp_year }}</div>
                                        </div>
                                    </label>
                                </div>
                            </template>
                            <template v-else>
                                <div class="rounded border border-2 border-dashed border-info mb-4 p-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="d-flex justify-content-between align-items-center gap-3 w-100">
                                            <h3 class="fs-4 fw-bolder mb-1">{{ user_card.card_holder_name }}</h3>
                                            <div class="border rounded bg-light-info text-info fw-bolder py-2 px-3 d-flex align-items-center gap-2">
                                                <span class="fs-3">Default</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-1 w-100">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fs-3 text-capitalize fw-bold mb-1">{{ user_card.brand }}</span>
                                            <div class="fs-2">**** **** **** {{ user_card.last4 }}</div>
                                        </div>
                                        <div class="fs-2">Card expires at {{ numFormat(default_card.exp_month) }}/{{ default_card.exp_year }}</div>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click.prevent="closePaymentMethodList">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent="setToDefaultPaymentMethod" :disabled="form.change_payment_method.paymentMethodId == ''">Set to Default</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" :class="{
        'd-none': (pm_list_toggle ? false : true)
    }"></div>

    <div id="add-new-card" class="modal fade" :class="{
        'show d-block': card_form_toggle == true
    }" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="fs-5 fw-bolder text-muted mb-0">Add New Card</h3>
                    <button type="button" class="btn p-0 outline-none border-none" @click.prevent="confirmDiscardNewCardForm">
                        <i class="ti ti-x fs-7"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="form-label mb-1" for="new-card-holder-name">Name On Card <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.add_new_card.card_holder_name" id="new-card-holder-name" class="form-control py-3" placeholder="e.g. John Doe">
                                <div class="text-danger" v-if="errors.card_holder_name">
                                    {{ errors.card_holder_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="form-label mb-1" for="new-card-element">Card Information <span class="text-danger">*</span></label>
                                <div id="new-card-element" class="form-control rounded py-3"></div>
                                <div class="text-danger" id="new-card-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <div class="form-check mb-0 d-flex align-items-center gap-2">
                                    <input type="checkbox" v-model="form.add_new_card.set_to_default" id="new-set_to_default" class="form-check-input">
                                    <label class="form-check-label fs-3 fw-bold text-muted" for="new-set_to_default">Set the card to default for further billing.</label>
                                </div>
                                <div class="text-danger" v-if="errors.set_to_default">
                                    {{ errors.set_to_default }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click.prevent="confirmDiscardNewCardForm">Discard</button>
                    <button type="button" class="btn btn-primary" @click.prevent="saveCard">Save Card</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" :class="{
        'd-none': (card_form_toggle ? false : true)
    }"></div>

    <div id="add-new-website" class="modal fade" :class="{
        'show d-block': website_createfrom_toggle == true
    }" style="z-index: 11000;" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="fs-5 fw-bolder text-muted mb-0">Add New Website</h3>
                    <button type="button" class="btn p-0 outline-none border-none" @click.prevent="closeAddNewWebsiteForm">
                        <i class="ti ti-x fs-7"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="website_name" class="form-label">Website Name <span class="text-danger">*</span></label>
                                <input type="text" id="website_name" class="form-control" v-model="website_form.website_name">
                                <div class="text-danger" v-if="errors.website_name">
                                    <small>{{ errors.website_name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="website_url" class="form-label">Website Url <span class="text-danger">*</span></label>
                                <input type="url" id="website_url" class="form-control" v-model="website_form.website_url">
                                <div class="text-danger" v-if="errors.website_url">
                                    <small>{{ errors.website_url }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click.prevent="closeAddNewWebsiteForm">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent="saveNewWebsite">Save Website</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" :class="{
        'd-none': (website_createfrom_toggle ? false : true)
    }" style="z-index: 10999;"></div>

    <div id="change-websites" class="modal fade" :class="{
        'show d-block': change_websites_toggle == true
    }" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="fs-5 fw-bolder text-muted mb-0">Select Website</h3>
                    <button type="button" class="btn p-0 outline-none border-none" @click.prevent="closeChangeWebsiteForm">
                        <i class="ti ti-x fs-7"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-column gap-1 mb-2">
                        <h3 class="mb-0 fs-3 fw-bolder">Websites <span class="text-danger">*</span></h3>
                        <p class="mb-0 fs-2 fw-bold text-muted">You can select {{ !current_subscription.package?.website_limit ? 'unlimited' :  numFormat(current_subscription.package?.website_limit) }} website</p>
                    </div>
                    <div class="form-group mb-5">
                        <template v-if="Object.keys(current_subscription).length>0">
                            <Multiselect v-model="selectedWebsite" :options="websites_options" :max="!current_subscription.package?.website_limit ? -1 : parseInt(current_subscription.package?.website_limit)" mode="tags" placeholder="Select Your Websites">
                                <template v-slot:option="{ option }">
                                    <div>
                                        <span class="d-block">{{ option.label }}</span>
                                        <span class="d-block fs-2 text-muted">{{ option.url }}</span>
                                    </div>
                                </template>
                            </Multiselect>
                        </template>
                        <div class="text-danger" v-if="errors.websites">
                            <small>{{ errors.websites }}</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click.prevent="closeChangeWebsiteForm">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent="saveSelectedWebsites">Save Website</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" :class="{
        'd-none': (change_websites_toggle ? false : true)
    }"></div>

    <div id="take-plan" class="modal fade" :class="{
        'show d-block': take_plan_toggle == true
    }" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header p-4 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn p-0 outline-none border-none" @click.prevent="takePlanClose">
                        <i class="ti ti-x fs-7"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 text-center">
                        <h1 class="fw-bolder mb-2 fs-8">Flexible Plans</h1>
                        <div class="text-muted fw-bold fs-4">Choose a plan that works best for you</div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <template v-if="packages.length>0">
                                <div class="row mb-5 gy-3 gx-3 justify-content-start">
                                    <template v-for="(item, index) in packages" :key="item.id">
                                        <div class="col-md-4" v-if="!(user.customer_details?.is_avail_free_plan == 1 && item.free_plan)">
                                            <div class="rounded-1 border border-2 p-4 h-100" :class="{
                                                'border-primary border-dashed': Object.keys(pack).length>0 && pack.id == item.id,
                                                'border-primary border-dashed opacity-50': isActivePackage(item)
                                            }" @click.prevent="packageSelectIfApplicable(item)">
                                                <span v-if="item.recommended" class="fw-bolder text-primary mb-1 d-inline-block text-uppercase fs-1 rounded-1">Best Deal</span>
                                                <div class="mb-3 d-flex flex-column gap-1">
                                                    <span class="fw-bolder text-uppercase fs-3 d-block">{{ item.title }}</span>
                                                    <div class="fs-2" v-if="item.description">{{ item.description }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <h2 class="fw-bolder fs-6 mb-0" v-if="item.free_plan">Free</h2>
                                                    <h2 class="fw-bolder fs-6 mb-0" v-else>
                                                        {{ priceFormat(item.price) }}
                                                        <span class="fs-2 text-capitalize">/ {{ item.duration_lifetime ? 'Lifetime' : item.format_duration }}</span>
                                                    </h2>
                                                    <div class="fs-3 fw-bold" v-if="item.sale_price !== null">Normally <del class="text-dark">{{ priceFormat(item.regular_price) }}</del></div>
                                                </div>
                                                <div class="fs-2 fw-bolder text-dark mb-3" v-if="item.trial_period_days && user.customer_details?.is_avail_trial == 0">{{ item.trial_period_days }} {{ item.trial_period_days > 1 ? 'Days' : 'Day' }} Free Trial</div>
                                                <template v-if="item.features">
                                                    <ul class="list-unstyled mb-0">
                                                        <template v-for="feature in JSON.parse(item.features)">
                                                            <li class="d-flex align-items-center gap-2 py-2 fs-2">
                                                                <i class="ti ti-check text-primary fs-4"></i>
                                                                <span class="text-dark">{{ feature }}</span>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="row justify-content-center mb-4" v-if="!(packages.length==1 && isActivePackage(packages[0]))">
                                    <div class="col-md-6">
                                        <button class="btn btn-primary w-100" @click.prevent="goToPayment()">{{ (Object.keys(pack).length>0) ? 'Countinue' : 'Select a Plan' }}</button>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="p-4 rounded my-5 bg-light d-flex justify-content-center flex-column gap-2 border border-2">
                                    <h4 class="fs-4 fw-bolder mb-2">No Plans Found!</h4>
                                    <p class="fs-2 mb-0">Please kindly contact the administrator for further assistance.</p>
                                    <p class="fs-2 mb-0">For any query please call us at <a :href="'tel:'+$page.props.app.support_number" class="text-dark fw-bold">{{ $page.props.app.support_number }}</a> or send an email to <a :href="'mailto:'+$page.props.app.support_mail" class="text-dark fw-bold">{{ $page.props.app.support_mail }}</a></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" :class="{
        'd-none': (take_plan_toggle ? false : true)
    }"></div>

    <div id="payment" class="modal fade" :class="{
        'show d-block': billing_payment_toggle == true && Object.keys(pack).length>0
    }" style="z-index: 10000;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn p-0 outline-none border-none" @click.prevent="billingPaymentClose">
                        <i class="ti ti-x fs-7"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-5 text-center">
                        <h1 class="text-dark fw-bolder fs-6 text-uppercase mb-3">Payment Information</h1>
                        <div class="text-muted fw-bold fs-3" v-if="!pack.free_plan">Please provide your payment information, including your name, credit card number, expiration date, and CVC code to complete the transaction.</div>
                        <div class="text-muted fw-bold fs-3" v-else>No payment information is required for our free plan.</div>
                    </div>
                    <template v-if="!pack.free_plan">
                        <template v-if="has_default_card === true && use_default_card == true">
                            <div class="mb-4">
                                <button type="button" class="btn btn-dark btn-sm" @click.prevent="paymentAddNewCard">Add New Card</button>
                            </div>
                            <div class="rounded border border-2 mb-4 p-3 d-flex flex-column gap-2">
                                <span class="text-dark fs-2 fw-bolder">DEFAULT CARD</span>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light border border-2 rounded p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-credit-card text-dark d-block fs-7"></i>
                                    </div>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fs-4 text-capitalize fw-bolder mb-1">{{ default_card.brand }} **** {{ default_card.last4 }}</div>
                                        <div class="fs-2">Card expires at {{ numFormat(default_card.exp_month) }}/{{ default_card.exp_year }}</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-if="use_default_card == false">
                            <div class="mb-4" v-if="has_default_card === true">
                                <button type="button" class="btn btn-info btn-sm" @click.prevent="use_default_card = true">Use Default Card</button>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label mb-1" for="new-card-holder-name">Name On Card <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.payment.card_holder_name" id="new-card-holder-name" class="form-control" placeholder="e.g. John Doe">
                                <div class="text-danger" v-if="errors.card_holder_name">
                                    {{ errors.card_holder_name }}
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label mb-1" for="payment-card-element">Card Information <span class="text-danger">*</span></label>
                                <div id="payment-card-element" class="form-control rounded py-3"></div>
                                <div class="text-danger" id="payment-card-errors"></div>
                            </div>
                        </template>
                        <div class="form-group mb-3">
                            <label class="form-label mb-1" for="payment-discount-code">Discount Code</label>
                            <input type="text" v-model="form.payment.discount_code" id="payment-discount-code" class="form-control" placeholder="e.g. EX123_TEST_CODE">
                            <div class="text-danger" v-if="errors.discount_code">
                                {{ errors.discount_code }}
                            </div>
                        </div>
                    </template>
                    <div class="form-group mb-5">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <label class="form-label mb-1">Websites <span class="text-danger">*</span></label>
                                <p class="mb-1 fs-2 fw-bold text-muted">You can select {{ !pack.website_limit ? 'unlimited' :  numFormat(pack.website_limit) }} website</p>
                            </div>
                            <button class="btn btn-info btn-sm fs-2" @click.prevent="openAddNewWebsiteForm">Add New Website</button>
                        </div>
                        <Multiselect v-model="selectedWebsite" :options="websites_options" :max="!pack.website_limit ? -1 : parseInt(pack.website_limit)" mode="tags" placeholder="Select Your Websites">
                            <template v-slot:option="{ option }">
                                <div>
                                    <span class="d-block">{{ option.label }}</span>
                                    <span class="d-block fs-2 text-muted">{{ option.url }}</span>
                                </div>
                            </template>
                        </Multiselect>
                        <div class="text-danger" v-if="errors.websites">
                            <small>{{ errors.websites }}</small>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 mt-5 mb-4">
                        <div class="d-flex flex-column gap-1">
                            <h4 class="fs-4 text-dark fw-bolder mb-0">Order Total</h4>
                            <div class="text-muted fw-bold fs-3">
                                {{ pack.title }}
                                <span class="text-dark fw-bold" v-if="pack.free_plan">Free</span>
                                <span class="text-dark fw-bold" v-else>{{ priceFormat(pack.price) }}</span>
                                <span class="fs-2 text-capitalize">/ {{ pack.duration_lifetime ? 'Lifetime' : pack.format_duration }}</span>
                                <template v-if="pack.trial_period_days && user.customer_details?.is_avail_trial == 0">
                                    with <span class="text-dark fw-bold">{{ pack.trial_period_days }} {{ pack.trial_period_days > 1 ? 'Days' : 'Day' }} Free Trial</span>
                                </template>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-primary" @click.prevent="payNow()">Pay Now <strong>{{ priceFormat(pack.price) }}</strong></button>
                            <button class="btn btn-info" @click.prevent="billingPaymentClose">Change Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" :class="{
        'd-none': (billing_payment_toggle ? false : true)
    }" style="z-index: 9999;"></div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';
import { loadStripe } from '@stripe/stripe-js/pure';
import Multiselect from '@vueform/multiselect';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
        Multiselect
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            subscription: {},
            current_subscription: {},
            default_card: {},
            packages: [],
            pack: {},
            user_cards: [],
            websites: [],
            websites_options: [],
            subsStatuses: {
                active: 'Active', // Subscription is currently active
                trialing: 'Trialing', // Subscription is in a trial period
                paused: 'Paused', //  If the subscription is paused
                past_due: 'Past Due', // Payment is overdue
                canceled: 'Canceled', // Subscription has been canceled
                incomplete: 'Incomplete', // Subscription is not fully set up or payment is pending
                incomplete_expired: 'Incomplete Expired', // Subscription setup expired due to incomplete setup
                unpaid: 'Unpaid', // Payment failed and subscription is in unpaid state
            },
            card_form_toggle: false,
            form: {
                add_new_card: {
                    card_holder_name: '',
                    paymentMethodId: '',
                    set_to_default: false
                },
                change_payment_method: {
                    paymentMethodId: ''
                },
                payment: {
                    card_holder_name: '',
                    paymentMethodId: '',
                    discount_code: ''
                }
            },
            payment_method: 'stripe',
            upgrade_subscription: false,
            pm_list_toggle: false,
            stripe: false,
            has_default_card: false,
            use_default_card: false,
            coupon: {
                discount_code: ''
            },
            cardElement: false,
            take_plan_toggle: false,
            billing_payment_toggle: false,
            selectedWebsite: [],
            website_form: {
                website_name: '',
                website_url: ''
            },
            website_createfrom_toggle: false,
            change_websites_toggle: false,
            loader: false,
            errors: {}
        };
    },

    methods: {
        discountPrice(original, value, type) {
            if (!original || !value || !type) return original;

            if (type === 'percent') {
                return (original - (original * value / 100)).toFixed(2);
            } else if (type === 'fixed') {
                return (original - value).toFixed(2);
            }
            return original;
        },
        checkExpiry(date) {
            if (!date) return false;
            const now = new Date();
            const expiryDate = new Date(date);
            return expiryDate > now;
        },

        numFormat(number) {
            return number > 9 ? number : '0'+number;
        },

        isActivePackage(item) {
            return (
                Object.keys(this.current_subscription).length>0 &&
                this.current_subscription.package_id == item.id
            );
        },

        packageSelectIfApplicable(item) {
            if ((this.user.customer_details?.is_avail_free_plan == 1 && item.free_plan) || this.isActivePackage(item)) {
                return;
            }

            this.packageSelect(item);
        },

        isMatchPlanId(pm_plan_id, payment_methods) {
            if (payment_methods.length > 0) {
                const pack_pm = payment_methods.find(
                    (payment_method) => payment_method.pm_price_id === pm_plan_id
                );
                return Boolean(pack_pm);
            }

            return false;
        },

        isExpired(expire_at) {
            let targetDate = moment('2024-06-07 08:46:54');
            let currentDate = moment();
            let isExpired = targetDate.isBefore(currentDate);
            return isExpired;
        },

        confirmDiscardNewCardForm() {
            Swal.fire({
                html: "Are you sure you would like to cancel?",
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, return',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger m-0",
                    cancelButton: "btn btn-active-light m-0"
                },
            }).then((result) => {
                if (result.isConfirmed) {
                   this.discardNewCardForm();
                }
            });
        },

        discardNewCardForm() {
            this.card_form_toggle = false;
            this.form.add_new_card.card_holder_name = this.user?.fullname;
            document.body.classList.remove('overflow-hidden');
            this.newCardInitPayment();
        },

        addNewCardForm() {
            this.card_form_toggle = true;
            this.errors = {};
            document.body.classList.add('overflow-hidden');
            this.newCardInitPayment();
        },

        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(2);
            return symbol ? this.$page.props.currency_symbol + price : price;
        },
        dateFormat(date, format, cformat = null) {
            if(cformat) {
                return moment(date, cformat).format(format);
            }

            return moment(date).format(format);
        },

        async newCardInitPayment() {
            this.stripe = await loadStripe(this.$page.props.stripe_key);
            this.elements = this.stripe.elements();
            this.cardElement = this.elements.create('card');
            this.cardElement.mount('#new-card-element');
            this.cardElement.addEventListener('change', (event) => {
                const displayError = document.getElementById('new-card-errors');
                if(event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        },

        async saveCard() {
            this.errors = {};

            if(this.card_holder_name === '') {
                this.errors['card_holder_name'] = 'Please enter a card holder name.';
            } else {
                this.loader = true;
                await this.stripe.createPaymentMethod({
                    type: 'card',
                    card: this.cardElement,
                    billing_details: {
                        name: this.form.add_new_card.card_holder_name
                    }
                }).then((result) => {
                    if(result.error) {
                        var errorElement = document.getElementById('new-card-errors');
                        errorElement.textContent = result.error.message;
                        this.loader = false;
                    } else {
                        this.form.add_new_card.paymentMethodId = result.paymentMethod.id;
                        this.addCard();
                    }
                });
            }
        },

        async addCard() {
            await axios.post(route('api.subscription.card.add'), {
                card_holder_name: this.form.add_new_card.card_holder_name,
                set_to_default: this.form.add_new_card.set_to_default,
                paymentMethodId: this.form.add_new_card.paymentMethodId,
                payment_method: this.payment_method
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },

        changePaymentMethod() {
            this.pm_list_toggle = true;
            this.errors = {};
            document.body.classList.add('overflow-hidden');
        },

        changePaymentMethodToggle(pm_id) {
            if(this.form.change_payment_method.paymentMethodId == pm_id) {
                this.form.change_payment_method.paymentMethodId = false;
                return;
            }

            this.form.change_payment_method.paymentMethodId = pm_id;
        },

        closePaymentMethodList() {
            this.form.change_payment_method.paymentMethodId = '';
            document.body.classList.remove('overflow-hidden');
            this.pm_list_toggle = false;
            this.errors = {};
        },

        async setToDefaultPaymentMethod() {
            this.loader = true;
            this.errors = {};
            await axios.post(route('api.subscription.card.change_payment_method'), {
                paymentMethodId: this.form.change_payment_method.paymentMethodId,
                payment_method: this.payment_method
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.loader = false;
                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },

        confirmRemovePaymentMethod(event, pm_id) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            this.errors = {};
            Swal.fire({
                html: 'Please confirm if you want remove this card.',
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Not Now',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger m-0",
                    cancelButton: "btn btn-active-light m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removePaymentMethod(ele, pm_id);
                }
            });
        },

        async removePaymentMethod(ele, pm_id) {
            ele.prop('disabled', true);
            this.loader = true;
            await axios.post(route('api.subscription.card.remove', [pm_id]), {
                payment_method: this.payment_method
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.has_default_card = true;
                this.use_default_card = true;
                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                ele.prop('disabled', false);
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        confirmAutoRenewalSubscription(event) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            this.errors = {};
            let newValue = this.user.customer_details?.auto_renewal_subscription ? 'disabled' : 'enabled';
            let confirm_button_class = this.user.customer_details?.auto_renewal_subscription ? 'btn-danger' : 'btn-info';

            Swal.fire({
                html: 'Please confirm if you want '+ newValue +' auto-renewal.',
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Yes, '+ newValue +' it!',
                cancelButtonText: 'Not Now',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn "+ confirm_button_class +" m-0",
                    cancelButton: "btn btn-active-light m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.changeAutoRenewal(ele);
                }
            });
        },

        async changeAutoRenewal(ele) {
            ele.prop('disabled', true);
            this.loader = true;
            await axios.post(route('api.subscription.change_auto_renewal'), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));

                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                ele.prop('disabled', false);
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        async getSubscription() {
            this.subscription = {};
            this.loader = true;
            this.errors = {};

            await axios.get(route('api.subscription.get.single'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.subscription = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        async getCurrentSubscription() {
            this.current_subscription = {};
            this.loader = true;

            await axios.get(route('api.subscription.current'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.current_subscription = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        async getDefaultCard() {
            this.default_card = {};
            this.loader = true;
            this.errors = {};

            await axios.get(route('api.subscription.card.default'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.default_card = $response.data;
                this.has_default_card = true;
                this.use_default_card = true;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        async getUserCards() {
            this.user_cards = {};
            this.loader = true;
            this.errors = {};

            await axios.get(route('api.subscription.card'), {
                params: {
                    unexpired: true
                },
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.user_cards = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        async getPackages() {
            this.packages = [];
            this.loader = true;
            this.errors = {};

            await axios.get(route('api.package.get.all'), {
                params: {
                    orderby: 'sort',
                    order: 'ASC',
                    // limit: 3,
                    status: 'active',
                    hide_private: 1
                },
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data.length > 0) {
                    this.packages = $response.data;
                }

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
            });
        },

        confirmResumeSubscription(event) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            Swal.fire({
                html: 'Please confirm if you want resume this subscription.',
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Resume Subscription',
                cancelButtonText: 'No, return',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-primary w-100 m-0",
                    cancelButton: "btn btn-active-light w-100 m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.resumeSubscription(ele);
                }
            });
        },

        async resumeSubscription(ele) {
            ele.prop('disabled', true);
            this.loader = true;
            await axios.post(route('api.subscription.resume'), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));

                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                ele.prop('disabled', false);
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        confirmCancelSubscription(event) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            Swal.fire({
                html: "Please confirm if you want cancel this subscription.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Cancel Subscription',
                cancelButtonText: 'No, return',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger w-100 m-0",
                    cancelButton: "btn btn-active-light w-100 m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.cancelSubscription(ele);
                }
            });
        },

        async cancelSubscription(ele) {
            ele.prop('disabled', true);
            this.loader = true;
            await axios.post(route('api.subscription.cancel'), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));

                this.current_subscription = {};
                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                ele.prop('disabled', false);
                this.loader = false;
            }).catch((error) => {
                ele.prop('disabled', false);
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        takePlan(upgrade = false) {
            this.take_plan_toggle = true;
            this.errors = {};
            this.upgrade_subscription = upgrade;
            document.body.classList.add('overflow-hidden');
        },

        takePlanClose() {
            this.take_plan_toggle = false;
            this.pack = {};
            this.errors = {};
            this.upgrade_subscription = false;
            document.body.classList.remove('overflow-hidden');
        },

        async billingInitPayment() {
            this.errors = {};
            this.stripe = await loadStripe(this.$page.props.stripe_key);
            this.elements = this.stripe.elements();
            this.cardElement = this.elements.create('card');
            if (document.getElementById('payment-card-element')) {
                this.cardElement.mount('#payment-card-element');
            }

            this.cardElement.addEventListener('change', (event) => {
                const displayError = document.getElementById('payment-card-errors');
                if(event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        },

        packageSelect(item) {
            if(Object.keys(this.pack).length>0 && this.pack.id == item.id) {
                this.pack = {};
                return;
            }

            this.pack = item;
        },

        goToPayment() {
            if(Object.keys(this.pack).length == 0) {
                toast.warning('Kindly choose a plan from the options available.');
                return;
            }

            this.billing_payment_toggle = true;
            this.errors = {};
            document.body.classList.add('overflow-hidden');
            this.billingInitPayment();
        },

        billingPaymentClose() {
            this.billing_payment_toggle = false;
            this.errors = {};
        },

        paymentAddNewCard() {
            this.use_default_card = false;
            this.errors = {};
            this.billingInitPayment();
        },

        async payNow() {
            if(this.pack.free_plan) {
                this.has_default_card = true;
                this.use_default_card = true;
            }

            this.errors = {};
            if(this.form.payment.card_holder_name === '' && this.use_default_card == false) {
                toast.error('Please enter a card holder name.');
            } else {
                this.loader = true;
                if(this.use_default_card === true || this.pack.free_plan) {
                    if(this.upgrade_subscription) {
                        this.upgradeSubscription();
                    } else {
                        this.createSubscription();
                    }
                } else {
                    await this.stripe.createPaymentMethod({
                        type: 'card',
                        card: this.cardElement,
                        billing_details: {
                            name: this.form.payment.card_holder_name
                        }
                    }).then((result) => {
                        if(result.error) {
                            var errorElement = document.getElementById('payment-card-errors');
                            errorElement.textContent = result.error.message;
                            this.loader = false;
                        } else {
                            this.form.payment.paymentMethodId = result.paymentMethod.id;
                            if(this.upgrade_subscription) {
                                this.upgradeSubscription();
                            } else {
                                this.createSubscription();
                            }
                        }
                    });
                }
            }
        },

        async createSubscription() {
            let opt = {
                default_card: true,
                package: this.pack.id,
                discount_code: this.form.payment.discount_code,
                payment_method: this.payment_method,
                websites: this.selectedWebsite
            };

            if(this.use_default_card === false) {
                opt.default_card = false;
                opt.paymentMethodId = this.form.payment.paymentMethodId;
                opt.card_holder_name = this.form.payment.card_holder_name;
            }

            await axios.post(route('api.subscription.payment'), opt, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));

                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },

        async upgradeSubscription() {
            let opt = {
                default_card: true,
                package: this.pack.id,
                discount_code: this.form.payment.discount_code,
                payment_method: this.payment_method,
                websites: this.selectedWebsite
            };

            if(this.use_default_card === false) {
                opt.default_card = false;
                opt.paymentMethodId = this.form.payment.paymentMethodId;
                opt.card_holder_name = this.form.payment.card_holder_name;
            }

            await axios.post(route('api.subscription.upgrade'), opt, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));

                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },

        async applyDiscount() {
            this.loader = true;
            this.errors = {};
            await axios.post(route('api.subscription.apply.discount'), this.coupon, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                document.body.classList.remove('overflow-hidden');
                this.$inertia.visit(route(route().current()));
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },

        async getWebsites() {
            this.websites_options = [];
            this.loader = true;
            await axios.get(route('api.website.get.all'), {
                params: {
                    orderby: 'website_name',
                    order: 'ASC',
                    status: 'active'
                },
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data.length>0) {
                    this.websites = $response.data;
                    $response.data.forEach((value) => {
                        this.websites_options.push({
                            value: value.id,
                            label: value.website_name,
                            url: value.website_url
                        });
                    });
                }
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        openChangeWebsiteForm() {
            this.selectedWebsite = [];
            let websiteIds = [];

            if(Object.keys(this.current_subscription).length>0 && this.current_subscription.websites.length>0) {
                this.current_subscription.websites.forEach((value, index) => {
                    websiteIds[index] = value.id;
                });
            }

            this.selectedWebsite = websiteIds;
            this.change_websites_toggle = true;
            document.body.classList.add('overflow-hidden');
        },

        closeChangeWebsiteForm() {
            this.change_websites_toggle = false;
            document.body.classList.remove('overflow-hidden');
        },

        async saveSelectedWebsites() {
            this.loader = true;
            this.errors = {};
            await axios.post(route('api.subscription.website_update'), {
                websites: this.selectedWebsite
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.getCurrentSubscription();
                this.getSubscription();
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },

        openAddNewWebsiteForm() {
            this.website_createfrom_toggle = true;
            document.body.classList.add('overflow-hidden');
        },

        closeAddNewWebsiteForm() {
            this.website_createfrom_toggle = false;
            document.body.classList.remove('overflow-hidden');
        },

        async saveNewWebsite() {
            this.loader = true;
            this.errors = {};
            await axios.post(route('api.website.create'), this.website_form, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.website_form.website_name = '';
                this.website_form.website_url = '';
                this.getWebsites();
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if (error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        }
    },

    mounted() {
        this.$refs.app_layout.loadScript();

        this.form.add_new_card.card_holder_name = this.user?.fullname;
        this.form.payment.card_holder_name = this.user?.fullname;

        let alertboxErrorMsg = this.$cookies.get('lxf-alertbox-error');
        if(alertboxErrorMsg != null) {
            Swal.fire({
                html: alertboxErrorMsg,
                icon: "error",
                customClass: {
                    htmlContainer: 'fs-4',
                    confirmButton: "btn btn-primary"
                },
            });
            this.$cookies.remove('lxf-alertbox-error');
        }

        let alertboxSuccessMsg = this.$cookies.get('lxf-alertbox-success');
        if(alertboxSuccessMsg != null) {
            Swal.fire({
                html: alertboxSuccessMsg,
                icon: "success",
                customClass: {
                    htmlContainer: 'fs-4',
                    confirmButton: "btn btn-primary"
                },
            });
            this.$cookies.remove('lxf-alertbox-success');
        }
    },

    created() {
        this.getPackages();
        this.getCurrentSubscription();
        this.getSubscription();
        this.getWebsites();
        this.getDefaultCard();
        this.getUserCards();
    }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
