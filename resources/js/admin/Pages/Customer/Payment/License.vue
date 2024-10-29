<template>
    <Head title="Plugin License" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Plugin License</template>
                <li class="breadcrumb-item"><Link :href="route('app.customer.subscription.index')" class="text-dark">Subscription</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Plugin License</li>
            </Breadcrumb>
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.subscription.index')" class="btn btn-primary btn-sm">All Subscriptions</Link>
                    <Link :href="route('app.customer.subscription.billing.history')" class="btn btn-primary btn-sm">Billing History</Link>
                </div>
            </div>
            <template v-if="license.status == 'deactive'">
                <div class="alert alert-danger mb-4">
                    <div class="d-flex align-items-center font-medium me-3 me-md-0">
                        <i class="ti ti-info-octagon fs-7 me-2 flex-shrink-0"></i>
                        <span class="fw-bold">To activate the license key, please upgrade your subscription</span>
                    </div>
                </div>
            </template>
            <div class="row">
                <div class="col-md-6 order-md-2">
                    <template v-if="Object.keys(current_subscription).length>0">
                        <template v-if="user.other.has_exceeded_leads_limit">
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
                                <div class="border border-2 rounded py-2 px-3 d-flex align-items-center gap-2">
                                    <span style="width: 7px;height: 7px;" class="rounded" :class="{
                                        'bg-success': current_subscription.status == 'active' || current_subscription.status == 'trialing',
                                        'bg-danger': current_subscription.status == 'unpaid' || current_subscription.status == 'past_due',
                                        'bg-warning': current_subscription.status == 'paused'
                                    }"></span>
                                    <span class="fs-3 fw-bold">{{ subsStatuses.hasOwnProperty(current_subscription.status) ? subsStatuses[current_subscription.status] : current_subscription.status }}</span>
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
                                    <span class="fs-3 fw-bolder">Website Limit:</span> 
                                    <span class="fs-3 fw-bold">{{ current_subscription.package?.website_limit ? (numFormat(current_subscription.websites.length) +' / '+ numFormat(current_subscription.package?.website_limit)) : 'Unlimited' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2" :class="{
                                    'text-danger': user.other.has_exceeded_leads_limit
                                }">
                                    <span class="fs-3 fw-bolder">Leads Limit:</span> 
                                    <span class="fs-3 fw-bold">{{ current_subscription.package?.lead_limit ? (current_subscription.leads +' / '+ current_subscription.package?.lead_limit) : 'Unlimited' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-3 fw-bolder">App Access:</span> 
                                    <span class="fs-3 fw-bold">{{ current_subscription.package?.app_access ? 'Yes' : 'No' }}</span>
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
                                <Link :href="route('app.customer.subscription.billing')" class="btn btn-primary">Manage Billing</Link>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="Object.keys(subscription).length>0">
                            <div class="rounded border border-2 p-4 mb-4">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                                    <h3 class="fs-5 fw-bolder text-muted mb-1">{{ subscription.name }}</h3>
                                    <div class="border border-2 rounded py-2 px-3 d-flex align-items-center gap-2">
                                        <span style="width: 7px;height: 7px;" class="rounded bg-danger"></span>
                                        <span class="fs-3 fw-bold">{{ subsStatuses.hasOwnProperty(subscription.status) ? subsStatuses[subscription.status] : subscription.status }}</span>
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
                                    <Link :href="route('app.customer.subscription.billing')" class="btn btn-primary">Take a Plan</Link>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
                <div class="col-md-6 order-md-1">
                    <div class="rounded border border-2 p-4 mb-4">
                        <span class="d-block fw-bold fs-2 mb-1 text-end">License Status : <span class="fw-bolder text-capitalize" :class="{
                            'text-info': license.status == 'active',
                            'text-danger': license.status == 'deactive'
                        }">{{ license.status }}</span></span>
                        <h4 class="fs-5 fw-bolder mb-3">Your License Key</h4>
                        <div class="border px-3 py-2 mb-4 rounded d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-3 license-key">
                                <template v-if="keyReveal">{{ license.uuid }}</template>
                                <template v-else>********************</template>
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="py-0 px-2 mx-2 text-muted border-end border-start" @click="copyLicenseKey()"><i class="ti fs-7 ti-copy"></i></span>
                                <span class="p-0 text-muted" @click="keyRevealToggle"><i class="ti fs-7" :class="{
                                    'ti-eye' : keyReveal,
                                    'ti-eye-off' : !keyReveal
                                }"></i></span>
                            </div>
                        </div>
                        <p class="mb-2 fs-3">To enable the pro features of the {{ $page.props.app.name }} plugin, you need to integrate the license key.</p>
                        <p class="mb-3 fs-3">Follow these steps for license key integration:</p>
                        <ol class="mb-3 fs-3">
                            <li class="mb-2">Copy this license key and open your WordPress CMS.</li>
                            <li class="mb-2">Navigate to <span class="text-dark fw-bold">Forms > Settings > License</span>.</li>
                            <li class="mb-2">Paste the license key into the "License Key" textbox, and then click the "Verify License" button.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';
import moment from 'moment';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            keyReveal: false,
            subscription: {},
            current_subscription: {},
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
            license: {},
            loader: false
        };
    },

    methods: {
        numFormat(number) {
            return number > 9 ? number : '0'+number;
        },

        keyRevealToggle() {
            this.keyReveal = this.keyReveal == true ? false : true; 
        },

        isExpired(expire_at) {
            let targetDate = moment('2024-06-07 08:46:54');
            let currentDate = moment();
            let isExpired = targetDate.isBefore(currentDate);
            return isExpired;
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

        copyLicenseKey() {
            const textElement = document.createElement('textarea');
            textElement.value = this.license.uuid;
            textElement.style.position = 'absolute';
            textElement.style.left = '-9999px';
            document.body.appendChild(textElement);
            textElement.select();
            document.execCommand('copy');
            document.body.removeChild(textElement);
            toast.success('License Copied');
        },

        async getLicense() {
            this.loader = true;
            await axios.get(route('api.subscription.get.license'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.license = $response.data;
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        async getCurrentSubscription() {
            this.loader = true;
            await axios.get(route('api.subscription.current'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if($response.data) {
                    this.current_subscription = $response.data;
                }
                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                console.log(error.response.data.message);
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
    },

    created() {
        this.getCurrentSubscription();
        this.getSubscription();
        this.getLicense();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>