<template>
<Head title="Pricing" />
<MasterLayout :loader="loader">
    <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="hero-content text-center">
                        <h1>Terms and Conditions</h1>
                        <!-- <div class="row">
                            <div class="col-lg-9">
                                <p>The Unlimited solution for creating custom forms and flows to connect users and enhance engagement and broaden your online presence.</p>
                                <div class="mt-5">
                                    <a href="#pricing" class="button button-s2 button-primary">Buy Now</a>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-section pricing-section overflow-hidden pb-250">
        <div class="container">
            <div class="pp-step">Step <span class="stepnum">1</span></div>
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10">
                    <div class="pp-head text-center">
                        <h3 class="pp-heading">Pick A Plan</h3>
                        <p>Choose A Subscription. You Can Upgrade At Any Time.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-8 col-sm-10">
                    <div class="pricing-area">
                        <template v-if="packages.length > 0">
                            <div class="row">
                                <template v-for="(item, index) in packages" :key="index">
                                    <div class="col-lg-4 pricing-box-wrap" :class="{
                                            'pricing-featured': item.recommended,
                                            'pricing-box-seleted': pack !== '' && pack.id == item.id
                                        }">
                                        <div class="pricing-box" :class="{
                                            'pricing-box-featured': item.recommended
                                        }">
                                            <span v-if="item.recommended"
                                                class="pricing-box-featured_label">Featured Plan</span>
                                            <div class="pricing-box-head">
                                                <h3 class="pricing-box-title">{{ item.title }}</h3>
                                                <div class="pricing-box-price">
                                                    <div class="pricing-box-amount">{{ priceFormat(item.price) }}
                                                    </div>
                                                    <span class="pricing-box-duration">/ {{ item.duration_lifetime ?
                                                        'Lifetime' : item.format_duration }}</span>
                                                </div>
                                                <template v-if="!user">
                                                    <button :href="`${route('pricing')}?plan=${item.id}`" class="button button-s2 button-primary button-block" :class="{
                                                        'button-primary': item.recommended
                                                    }" @click="initPayment(item)">
                                                        {{ (pack !== '' && pack.id == item.id) ? "Selected" : "Get Started →" }}
                                                    </button>
                                                </template>
                                                <template v-else-if="user.user_type === 'customer'">
                                                    <Link :href="route('app.customer.subscription.billing')" class="button button-s2 button-primary button-block" :class="{
                                                        'button-primary': item.recommended
                                                    }">Upgrade →</Link>
                                                </template>


                                                <!-- <Link v-if="!user" :href="`${route('pricing')}?plan=${item.id}`"
                                                    class="button button-s2 button-primary button-block">Get Started →
                                                </Link>
                                                <Link v-else-if="user.user_type === 'customer'"
                                                    :href="route('app.customer.subscription.billing')"
                                                    class="button button-s2 button-primary button-block">Upgrade →
                                                </Link> -->
                                            </div>
                                            <div class="pricing-box-body" v-if="item.features">
                                                <h4 class="pricing-box-title">Features</h4>
                                                <ul class="pricing-box-features">
                                                    <li v-for="(feature, index) in JSON.parse(item.features)"
                                                        :key="index">{{ feature }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <!-- <template v-if="packages.length>0">
                            <div class="row">
                                <template v-for="(item, index) in packages" :key="index">
                                    <div class="col-lg-4">
                                        <div class="pricing-box pricing-box2" :class="{
                                            'pricing-box-featured': item.recommended,
                                            'pricing-box-seleted': pack !== '' && pack.id == item.id
                                        }">
                                            <span v-if="item.recommended" class="pricing-box-featured_label">Featured Plan</span>
                                            <div class="pricing-box-head">
                                                <h3 class="pricing-box-title">{{ item.title }}</h3>
                                                <div class="pricing-box-price">
                                                    <div class="pricing-box-amount">{{ priceFormat(item.price) }}</div>
                                                    <span class="pricing-box-duration">/ {{ item.duration_lifetime ? 'Lifetime' : item.format_duration }}</span>
                                                </div>
                                                <template v-if="!user">
                                                    <button :href="`${route('pricing')}?plan=${item.id}`" class="button button-s2 button-block" :class="{
                                                        'button-primary': item.recommended
                                                    }" @click="initPayment(item)">
                                                        {{ (pack !== '' && pack.id == item.id) ? "Selected" : "Buy Now" }}
                                                    </button>
                                                </template>
                                                <template v-else-if="user.user_type === 'customer'">
                                                    <Link :href="route('app.customer.subscription.billing')" class="button button-s2 button-block" :class="{
                                                        'button-primary': item.recommended
                                                    }">Upgrade</Link>
                                                </template>
                                            </div>
                                            <div class="pricing-box-body" v-if="item.features">
                                                <h3 class="pricing-box-title text-uppercase">Features</h3>
                                                <ul class="pricing-box-features">
                                                    <li v-for="(feature, index) in JSON.parse(item.features)" :key="index">{{ feature }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template> -->
                        <template v-else>
                            <div class="notfound">No Packages Found</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <template v-if="pack !== ''">
        <section class="pp-section bg-light-green choose-websites-section" id="choose-websites">
            <div class="container">
                <div class="pp-step">Step <span class="stepnum">2</span></div>
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-10">
                        <div class="pp-head text-center">
                            <h3 class="pp-heading">Add Your Website</h3>
                            <p>You can select {{ pack.is_website_unlimited ? 'unlimited' : pack.website_limit }} website. Selected website you can change at any time:</p>
                        </div>
                    </div>
                </div>
                <div class="ppws-panel">
                    <div class="ppws-repeater">
                        <div class="ws-item">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-12 order-lg-last">
                                    <div class="ws-add-item">
                                        <label class="input-label d-lg-block d-none">&nbsp;</label>
                                        <button
                                            class="button button-s2 button-primary button-block"
                                            :disabled="pack.website_limit != null && websites.length >= pack.website_limit"
                                            @click="addWebsite()">
                                            Add Website
                                        </button>
                                        <div class="text-danger my-1 d-lg-block d-none" v-if="errors['websites.0.website_name'] || errors['websites.0.website_url']"><small>&nbsp;</small></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 order-lg-first">
                                    <div class="form-group2">
                                        <label class="input-label" for="websites-0-website_name">Website Name<span class="text-danger">*</span></label>
                                        <input type="text" v-model="websites[0].website_name" id="websites-0-website_name" class="input-control2" placeholder="Enter Website Name">
                                        <div class="text-danger my-1" v-if="errors['websites.0.website_name']">
                                            <small>{{ (errors['websites.0.website_name']).replace('websites.0.website_name', 'website name') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 order-lg-first">
                                    <div class="form-group2">
                                        <label class="input-label" for="websites-0-website_url">Website URL<span class="text-danger">*</span></label>
                                        <input type="text" v-model="websites[0].website_url" id="websites-0-website_url" class="input-control2" placeholder="Enter Website URL">
                                        <div class="text-danger my-1" v-if="errors['websites.0.website_url']">
                                            <small>{{ (errors['websites.0.website_url']).replace('websites.0.website_url', 'website url') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template v-if="websites.length>1">
                            <template v-for="(website, index) in websites" :key="index">
                                <div class="ws-item" v-if="index>0">
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 col-md-5">
                                            <div class="form-group2">
                                                <label class="input-label" :for="'websites-'+index+'-website_name'">Website Name<span class="text-danger">*</span></label>
                                                <input type="text" v-model="websites[index].website_name" :id="'websites-'+index+'-website_name'" class="input-control2" placeholder="Enter Website Name">
                                                <div class="text-danger my-1" v-if="errors['websites.'+index+'.website_name']">
                                                    <small>{{ (errors['websites.'+index+'.website_name']).replace('websites.'+index+'.website_name', 'website name') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-5">
                                            <div class="form-group2">
                                                <label class="input-label" :for="'websites-'+index+'-website_url'">Website URL<span class="text-danger">*</span></label>
                                                <input type="text" v-model="websites[index].website_url" :id="'websites-'+index+'-website_url'" class="input-control2" placeholder="Enter Website URL">
                                                <div class="text-danger my-1" v-if="errors['websites.'+index+'.website_url']">
                                                    <small>{{ (errors['websites.'+index+'.website_url']).replace('websites.'+index+'.website_url', 'website url') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group2">
                                                <label class="input-label d-md-block d-none">&nbsp;</label>
                                                <button class="button button-s2 button-light--danger button-block" @click="websites.splice(index, 1)">Remove</button>
                                                <div class="text-danger my-1 d-lg-block d-none" v-if="errors['websites.'+index+'.website_name'] || errors['websites.'+index+'.website_url']"><small>&nbsp;</small></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </section>
        <section class="pp-section payment-section">
            <div class="container">
                <div class="pp-step">Step <span class="stepnum">3</span></div>
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-10">
                        <div class="pp-head text-center">
                            <h3 class="pp-heading">Payment Information</h3>
                            <p>Please provide your payment information, including your name, credit card number, expiration date, and CVC code to complete the transaction.</p>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 col-md-10">
                        <div class="payment-form-wrap">
                            <div class="row">
                                <template v-if="pack.free_plan">
                                    <div class="col-md-12">
                                        <div class="form-group3">
                                            <label class="input-label" for="fullname">Fullname <span class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <div class="input-group2-append"><i class="bi bi-person"></i></div>
                                                <input type="text" v-model="fullname" id="fullname" class="input-control2 border-0" placeholder="Enter Your Fullname">
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.fullname">
                                                <small>{{ errors.fullname }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="col-md-12">
                                    <div class="form-group3">
                                        <label class="input-label" for="email-address">Email Address<span class="text-danger">*</span></label>
                                        <div class="input-group2">
                                            <div class="input-group2-append"><img src="/_public_assets/img/email-address.png" alt="Email Address"></div>
                                            <input type="email" v-model="email" id="email-address" class="input-control2 border-0" placeholder="Enter Email Address">
                                        </div>
                                        <div class="text-danger my-1" v-if="errors.email">
                                            <small>{{ errors.email }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group3">
                                        <label class="input-label" for="password">Password<span class="text-danger">*</span></label>
                                        <div class="input-group2">
                                            <div class="input-group2-append"><img src="/_public_assets/img/email-address.png" alt="Password"></div>
                                            <input :type="this.isRevealPassword ? 'text' : 'password'" v-model="password" id="password" class="input-control2 border-0" placeholder="Enter Password">
                                            <span class="input-group-text fs-5" @click="revealPassword()">
                                                <i class="ti" :class="this.isRevealPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                                            </span>
                                        </div>
                                        <div class="text-danger my-1" v-if="errors.email">
                                            <small>{{ errors.password }}</small>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="!pack.free_plan">
                                    <div class="col-md-12">
                                        <div class="form-group3">
                                            <label class="input-label" for="card-holder-name">Name on the Card<span class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <div class="input-group2-append"><img src="/_public_assets/img/name-on-the-card.png" alt="Name on the Card"></div>
                                                <input type="text" v-model="card_holder_name" id="card-holder-name" class="input-control2 border-0" placeholder="Enter Name On Credit/Debit Card">
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.card_holder_name">
                                                <small>{{ errors.card_holder_name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group3">
                                            <label class="input-label" for="card-information">Card Information<span class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <div class="input-group2-append"><img src="/_public_assets/img/card-information.png" alt="Card Information"></div>
                                                <div id="card-element"></div>
                                            </div>
                                            <div id="card-errors" class="text-danger my-1"></div>
                                        </div>
                                    </div>
                                </template>
                                <div class="col-md-12">
                                    <div class="form-group3">
                                        <label class="input-label" for="discount-code">Discount Code</label>
                                        <div class="input-group2">
                                            <div class="input-group2-append"><img src="/_public_assets/img/discount-code.png" alt="Discount Code"></div>
                                            <input type="text" v-model="discount_code" id="discount-code" class="input-control2 border-0" placeholder="e.g. EX123-321">
                                        </div>
                                        <div class="text-danger my-1" v-if="errors.discount_code">
                                            <small>{{ errors.discount_code }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-10">
                        <div class="payment-info">
                            <div class="mb-1">
                                <h3 class="payment-info-title">Order Total</h3>
                                <p>
                                    {{ pack.title }} <strong>{{ priceFormat(pack.price) }}</strong> / {{ pack.duration_lifetime ? 'Lifetime' : pack.format_duration }}
                                    <br/>
                                    Handling Fee <strong>${{handlingFee()}}</strong>
                                    {{ pack.trial_period_days ? ' with '+ pack.trial_period_days +' '+ (pack.trial_period_days > 1 ? 'Days' : 'Day') + ' Free Trial' : '' }}
                                </p>
                            </div>
                            <button class="button button-s2 button-white button-block" @click="payNow()">Pay Now <strong>{{ totalAmount()  }}</strong></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="pt-0 pp-section">
            <!-- BEGIN::BRAND LOGOs COMPONENT -->
            <BrandLogos></BrandLogos>
            <!-- END::BRAND LOGOs COMPONENT -->
        </section>
    </template>
</MasterLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';
import BrandLogos from '@/frontend/Components/BrandLogos.vue';
import { loadStripe } from '@stripe/stripe-js/pure';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    components: {
        Head,
        Link,
        MasterLayout,
        BrandLogos
    },

    data() {
        return {
            urlParams: new URLSearchParams(window.location.search),
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            packages: [],
            pack: '',
            stripe: false,
            cardElement: false,
            paymentMethodId: '',
            email: '',
            password: '',
            fullname: '',
            websites: [
                {
                    website_name: '',
                    website_url: ''
                }
            ],
            card_holder_name: '',
            discount_code: '',
            errors: {},
            isRevealPassword: false,
            loader: false
        };
    },

    methods: {
        revealPassword() {
            this.isRevealPassword = this.isRevealPassword ? false : true;
        },

        handlingFee() {
            let price = Number(this.pack.price) || 0;
            let fee = price * 0.0175 + 0.30;
            return fee.toFixed(2);
        },
        totalAmount() {
            let total = Number(this.pack.price) + Number(this.handlingFee());
            return this.$page.props.currency_symbol + total.toFixed(2);
        },
        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(0);
            return symbol ? this.$page.props.currency_symbol + price : price;
        },

        dateFormat(date, format, cformat = null) {
            if(cformat) {
                return moment(date, cformat).format(format);
            }

            return moment(date).format(format);
        },

        async getPackages() {
            await axios.get(route('api.guest.get.packages'), {
                params: {
                    orderby: 'sort',
                    order: 'ASC',
                    status: 'active',
                    limit: 3
                },
                headers: {
                    "Content-Type": "application/json"
                },
            }).then((response) => {
                let $response = response.data;
                this.packages = $response.data;
                if(!this.user) {
                    if(this.urlParams.get('plan')) {
                        if(this.packages.length) {
                            this.packages.forEach((item) => {
                                if(item.id == this.urlParams.get('plan')) {
                                    this.initPayment(item);
                                }
                            });
                        }
                    }
                }
            }).catch((error) => {
                console.log(error.response.data.message);
            });
        },

        addWebsite() {
            if(this.pack != '') {
                if(this.pack.website_limit == null) {
                    this.websites.push({
                        website_name: '',
                        website_url: ''
                    });
                } else {
                    if(this.websites.length < this.pack.website_limit) {
                        this.websites.push({
                            website_name: '',
                            website_url: ''
                        });
                    }
                }
            }
        },

        async initPayment(pack) {
            if(this.pack == pack) {
                this.pack = '';
                return;
            }

            this.errors = {};
            this.pack = pack;
            console.log(this.pack);
            setTimeout(function() {
                if($("#choose-websites").length) {
                    $('html, body').animate({ scrollTop: $("#choose-websites").offset().top - 120}, 0);
                }
            }, 100);

            if(!this.pack.free_plan) {
                this.stripe = await loadStripe(this.$page.props.stripe_key);
                this.elements = this.stripe.elements();
                this.cardElement = this.elements.create('card', {
                    style: {
                        base: {
                            color: '#000000',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#000000',
                            },
                        }
                    }
                });
                this.cardElement.mount('#card-element');
                this.cardElement.addEventListener('change', function(event) {
                    const displayError = document.getElementById('card-errors');
                    if(event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            }
        },

        async payNow() {
            this.errors = {};
            this.loader = true;
            let form = {
                email: this.email,
                password: this.password,
                fullname: this.fullname,
                package: this.pack.id,
                websites: this.websites,
                card_holder_name: this.card_holder_name,
                payment_method: 'stripe'
            };

            await axios.post(route('api.guest.validate.subscription'), form, {
                headers: {
                    "Content-Type": "application/json"
                },
            }).then((response) => {
                let $response = response.data;
                if($response.error == 0) {
                    if(!this.pack.free_plan) {
                        this.create_payment_method();
                    } else {
                        this.createSubscription();
                    }
                } else {
                    toast.error($response.message);
                }
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

        create_payment_method() {
            this.errors = {};
            this.stripe.createPaymentMethod({
                type: 'card',
                card: this.cardElement,
                billing_details: {
                    name: this.card_holder_name
                }
            }).then((result) => {
                if(result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    this.loader = false;
                } else {
                    this.paymentMethodId = result.paymentMethod.id;
                    this.createSubscription();
                }
            });
        },

        async createSubscription() {
            this.errors = {};
            let form = {
                email: this.email,
                password: this.password,
                fullname: this.fullname,
                package: this.pack.id,
                websites: this.websites,
                card_holder_name: this.card_holder_name,
                payment_method: 'stripe',
                paymentMethodId: this.paymentMethodId,
                discount_code: this.discount_code
            };

            await axios.post(route('api.guest.create.subscription'), form, {
                headers: {
                    "Content-Type": "application/json"
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 60);
                this.$cookies.set('lxf-token', $response.data.authorisation.token, $response.data.authorisation.expiration);
                this.$cookies.set('lxf-user', $response.data.user, $response.data.authorisation.expiration);

                window.location.href = route('app.customer.subscription.index');
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

    },

    created() {
        this.getPackages();
    }
}
</script>
