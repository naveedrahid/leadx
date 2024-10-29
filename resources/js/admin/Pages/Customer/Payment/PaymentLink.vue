<template>
    <Head title="Payment" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Payment</template>
                <li class="breadcrumb-item"><Link :href="route('app.customer.subscription.index')" class="text-dark">Subscription</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Payment</li>
            </Breadcrumb>
            <div class="row">
                <div class="col-md-6">
                    <div class="rounded border border-2 p-4 mb-4" v-if="Object.keys(package).length>0">
                        <div class="mb-5">
                            <h1 class="text-dark fw-bolder fs-6 text-uppercase mb-2">Payment Information</h1>
                            <div class="text-muted fw-bold fs-3" v-if="!package.free_plan">Please provide your payment information, including your name, credit card number, expiration date, and CVC code to complete the transaction.</div>
                            <div class="text-muted fw-bold fs-3" v-else>No payment information is required for our free plan.</div>
                        </div>
                        <template v-if="!package.free_plan">
                            <template v-if="has_default_card === true && use_default_card == true">
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <button type="button" class="btn btn-dark btn-sm" @click.prevent="paymentAddNewCard">Add New Card</button>
                                    <button class="btn btn-info btn-sm" @click.prevent="changePaymentMethod" v-if="user_cards.length>0">Change Payment Method</button>
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
                                <div class="d-flex align-items-center gap-2 mb-4" v-if="has_default_card === true">
                                    <button type="button" class="btn btn-dark btn-sm" @click.prevent="use_default_card = true">Use Default Card</button>
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
                                    <p class="mb-1 fs-2 fw-bold text-muted">You can select {{ !package.website_limit ? 'unlimited' :  numFormat(package.website_limit) }} website</p>
                                </div>
                                <button class="btn btn-info btn-sm fs-2" @click.prevent="openAddNewWebsiteForm">Add New Website</button>
                            </div>
                            <Multiselect v-model="selectedWebsite" :options="websites_options" :max="!package.website_limit ? -1 : parseInt(package.website_limit)" mode="tags" placeholder="Select Your Websites">
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
                                    {{ package.title }}
                                    <span class="text-dark fw-bold" v-if="package.free_plan">Free</span>
                                    <span class="text-dark fw-bold" v-else>{{ priceFormat(package.price) }}</span> 
                                    <span class="fs-2 text-capitalize">/ {{ package.duration_lifetime ? 'Lifetime' : package.format_duration }}</span>
                                    <template v-if="package.trial_period_days && user.customer_details?.is_avail_trial == 0">
                                        with <span class="text-dark fw-bold">{{ package.trial_period_days }} {{ package.trial_period_days > 1 ? 'Days' : 'Day' }} Free Trial</span>
                                    </template>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary" @click.prevent="payNow()">Pay Now <strong>{{ priceFormat(package.price) }}</strong></button>
                            </div>
                        </div>
                    </div>
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
                                        <div class="fs-2">Card expires at {{ numFormat(user_card.exp_month) }}/{{ user_card.exp_year }}</div>
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

    <div id="add-new-website" class="modal fade" :class="{
        'show d-block': website_createfrom_toggle == true
    }" tabindex="-1">
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
    }"></div>
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
            current_subscription: {},
            package: {},
            websites: [],
            websites_options: [],
            selectedWebsite: [],
            website_form: {
                website_name: '',
                website_url: ''
            },
            website_createfrom_toggle: false,
            change_websites_toggle: false,
            user_cards: [],
            pm_list_toggle: false,
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
            stripe: false,
            cardElement: false,
            payment_method: 'stripe',
            upgrade_subscription: false,
            default_card: {},
            has_default_card: false,
            use_default_card: false,
            take_plan_toggle: false,
            billing_payment_toggle: false,
            loader: false,
            errors: {}
        };
    },

    methods: {
        numFormat(number) {
            return number > 9 ? number : '0'+number;
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

                if(this.current_subscription.status != 'unpaid' && this.current_subscription.status != 'past_due') {
                    this.upgrade_subscription = true;
                }

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

        async getPaymentLink() {
            this.package = {};
            this.loader = true;
            this.errors = {};

            await axios.get(route('api.subscription.payment_link', [this.$page.props.uuid]), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                if(Object.keys($response.data).length>0) {
                    this.package = $response.data.package;

                    if(Object.keys(this.current_subscription).length>0) {
                        if(this.package.id == this.current_subscription.package_id) {
                            this.$cookies.set('lxf-warning-msg', 'You are already subscribed to this plan.', 10);
                            this.$inertia.visit(route('app.customer.subscription.billing'));
                        }
                    }
                } else {
                    this.$cookies.set('lxf-error-msg', 'Payment Link Expired!', 10);
                    this.$inertia.visit(route('app.customer.subscription.billing'));
                }
                
                this.loader = false;
            }).catch((error) => {
                this.$cookies.set('lxf-error-msg', error.response.data.message, 10);
                this.$inertia.visit(route('app.customer.subscription.billing'));
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
                this.$inertia.visit(route(route().current(), [this.$page.props.uuid]));
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

        closePaymentMethodList() {
            this.form.change_payment_method.paymentMethodId = '';
            document.body.classList.remove('overflow-hidden');
            this.pm_list_toggle = false;
            this.errors = {};
        },

        paymentAddNewCard() {
            this.use_default_card = false;
            this.errors = {};
            this.billingInitPayment();
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

        openAddNewWebsiteForm() {
            this.website_createfrom_toggle = true;
            document.body.classList.add('overflow-hidden');
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
        },

        closeAddNewWebsiteForm() {
            this.website_createfrom_toggle = false;
            document.body.classList.remove('overflow-hidden');
        },

        async payNow() {
            if(this.package.free_plan) {
                this.has_default_card = true;
                this.use_default_card = true;
            }

            this.errors = {};
            if(this.form.payment.card_holder_name === '' && this.use_default_card == false) {
                toast.error('Please enter a card holder name.');
            } else {
                this.loader = true;
                if(this.use_default_card === true || this.package.free_plan) {
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
                uuid: this.$page.props.uuid,
                default_card: true,
                package: this.package.id,
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
                this.$inertia.visit(route('app.customer.subscription.billing'));
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
                uuid: this.$page.props.uuid,
                default_card: true,
                package: this.package.id,
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
                this.$inertia.visit(route('app.customer.subscription.billing'));
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
        
        this.billingInitPayment();

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
        this.getCurrentSubscription();
        this.getSubscription();
        this.getWebsites();
        this.getDefaultCard();
        this.getUserCards();
        this.getPaymentLink();
    }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>