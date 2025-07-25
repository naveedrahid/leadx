<template>

    <Head title="Pricing" />
    <MasterLayout :loader="loader">
        <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-10 col-md-8">
                        <div class="hero-content text-center">
                            <h1 v-if="!pack.id">Get started with <span class="text-primary">LeadXForms</span> today.
                            </h1>
                            <p v-if="!pack.id">The Unlimited solution for creating custom forms and flows to connect
                                users and enhance
                                engagement and broaden your online presence.</p>
                            <h1 v-else>Checkout</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section pricing-section overflow-hidden pb-0 mt-4" v-if="route().current('pricing')">
            <div class="container">
                <!-- <div class="pp-step">Step <span class="stepnum">1</span></div> -->
                <!-- <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-10">
                        <div class="pp-head text-center">
                            <h3 class="pp-heading">Pick A Plan</h3>
                            <p>
                                Choose A Subscription. You Can Upgrade At Any
                                Time.
                            </p>
                        </div>
                    </div>
                </div> -->
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-8 col-sm-10">
                        <div class="pricing-area">
                            <template v-if="packages.length > 0">
                                <div class="row">
                                    <template v-for="(item, index) in packages" :key="index">
                                        <div class="col-lg-4 pricing-box-wrap" :class="{
                                            'pricing-featured':
                                                item.recommended,
                                            'pricing-box-seleted':
                                                pack !== '' &&
                                                pack.id == item.id,
                                        }">
                                            <div class="pricing-box" :class="{
                                                'pricing-box-featured':
                                                    item.recommended,
                                            }">
                                                <span v-if="item.recommended"
                                                    class="pricing-box-featured_label">Featured Plan</span>
                                                <div class="pricing-box-head">
                                                    <h3 class="pricing-box-title">
                                                        {{ item.title }}
                                                    </h3>
                                                    <div class="pricing-box-price">
                                                        <div class="pricing-box-amount">{{ getDisplayPrice(item) }}</div>
                                                        <span class="pricing-box-duration">/
                                                            {{
                                                                item.duration_lifetime
                                                                    ? "Lifetime"
                                                                    : item.format_duration
                                                            }}</span>
                                                    </div>
                                                    <template v-if="!user">
                                                        <Link :href="`${route('checkout')}?plan=${item.id}`"
                                                            class="button button-s2 button-primary button-block"
                                                            :class="{ 'button-primary': item.recommended }">
                                                            {{ (pack !== '' && pack.id == item.id) ? "Selected" : "Get Started →" }}
                                                        </Link>
                                                    </template>
                                                    <template v-else-if="
                                                        user.user_type ===
                                                        'customer'
                                                    ">
                                                        <a :href="route('app.customer.subscription.billing')"
                                                            class="button button-s2 button-primary button-block"
                                                            :class="{'button-primary':item.recommended,'button-secondary':!item.recommended}">Upgrade →</a>
                                                    </template>
                                                </div>
                                                <div class="pricing-box-body" v-if="item.features">
                                                    <h4 class="pricing-box-title">
                                                        Features
                                                    </h4>
                                                    <ul class="pricing-box-features">
                                                        <li v-for="(feature, index) in JSON.parse(item.features)"
                                                            :key="index">
                                                            {{ feature }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template v-else>
                                <div class="notfound">No Packages Found</div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center border-cst p-4 bg-light-green guarantee_mobile">
                    <div class="col-lg-3 col-md-5 col-sm-12">
                        <img src="/_public_assets/testImg/Money-Back-Guarantee-1.svg" width="250" height="250"
                            alt="Back-Guarantee img-fluid" />
                    </div>
                    <div class="col-lg-9 col-md-7 col-sm-12">
                        <h3 class="fw-bolder text-dark text-capitalize">Our 100% No-Risk money back guarantee!</h3>
                        <p class="inter-text">
                            We’re excited to have you experience UltimateAI
                            Pro. Over the next 30 days, if UltimateAI isn’t
                            the best fit, simply reach out! We’ll happily
                            refund 100% of your money. No questions asked.
                        </p>
                        <img src="/_public_assets/testImg/payment2-1.webp" width="350" alt="payment cards" />
                    </div>
                </div>
            </div>
        </section>
        <template v-if="pack.id">
            <section class="pp-section choose-websites-section" id="choose-websites">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7 col-md-10">
                            <div class="payment-form-wrap">
                                <div class="">
                                    <h3 class="text-black mb-4 fw-bolder">New Account Information</h3>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 order-lg-first">
                                        <div class="form-group3">
                                            <label class="input-label" for="websites-0-website_name">Website Name<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <input type="text" v-model="websites[0].website_name"
                                                    id="websites-0-website_name" class="input-control2"
                                                    placeholder="Website Name*" />
                                            </div>
                                            <div class="text-danger my-1" v-if="errors['websites.0.website_name']">
                                                <small>{{
                                                    errors[
                                                        "websites.0.website_name"
                                                    ].replace(
                                                        "websites.0.website_name",
                                                        "website name"
                                                    )
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 order-lg-first">
                                        <div class="form-group3">
                                            <label class="input-label" for="websites-0-website_url">Website URL<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <input type="text" v-model="websites[0].website_url"
                                                    id="websites-0-website_url" class="input-control2"
                                                    placeholder="Website URL*" />
                                            </div>
                                            <div class="text-danger my-1" v-if="errors['websites.0.website_url']">
                                                <small>{{
                                                    errors[
                                                        "websites.0.website_url"
                                                    ].replace(
                                                        "websites.0.website_url",
                                                        "website url"
                                                    )
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group3">
                                            <label class="input-label" for="first_name">First Name
                                                <span class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <input type="text" v-model="first_name" id="first_name"
                                                    class="input-control2 border-0" placeholder="First Name*" />
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.first_name">
                                                <small>{{
                                                    errors.first_name
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group3">
                                            <label class="input-label" for="last_name">Last Name</label>
                                            <div class="input-group2">
                                                <input type="text" v-model="last_name" id="last_name"
                                                    class="input-control2 border-0" placeholder="Last Name*" />
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.last_name">
                                                <small>{{
                                                    errors.last_name
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group3">
                                            <label class="input-label" for="email-address">Email Address<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <input type="email" v-model="email" id="email-address"
                                                    class="input-control2 border-0" placeholder="Email Address*" />
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.email">
                                                <small>{{
                                                    errors.email
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group3">
                                            <label class="input-label" for="phone-number">Phone Number<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <input type="tel" v-model="phone_number" id="phone-number"
                                                    class="input-control2 border-0" placeholder="Enter Phone Number*" />
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.phone_number">
                                                <small>{{
                                                    errors.phone_number
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 password">
                                        <div class="form-group3">
                                            <label class="input-label" for="password">Password<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group2">
                                                <input :type="this.isRevealPassword
                                                    ? 'text'
                                                    : 'password'
                                                    " v-model="password" id="password" class="input-control2 border-0"
                                                    placeholder="Enter Password*" />
                                                <span class="input-group-text fs-5" @click="revealPassword()">
                                                    <i class="ti" :class="this
                                                        .isRevealPassword
                                                        ? 'ti-eye'
                                                        : 'ti-eye-off'
                                                        "></i>
                                                </span>
                                            </div>
                                            <div class="text-danger my-1" v-if="errors.password">
                                                <small>{{
                                                    errors.password
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4" v-if="!pack.free_plan"></div>
                                </div>
                                <template v-if="!pack.free_plan">
                                    <div class="">
                                        <h3 class="text-black mb-4 fw-bolder">Payment Information</h3>
                                    </div>
                                    <div class="paymentCard shadow-sm">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group3">
                                                    <label class="input-label" for="card-holder-name">Name on the
                                                        Card<span class="text-danger">*</span></label>
                                                    <div class="input-group2">
                                                        <input type="text" v-model="card_holder_name
                                                            " id="card-holder-name" class="input-control2 border-0"
                                                            placeholder="Enter Name On Credit/Debit Card*" />
                                                    </div>
                                                    <div class="text-danger my-1" v-if="
                                                        errors.card_holder_name
                                                    ">
                                                        <small>{{
                                                            errors.card_holder_name
                                                        }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group3 mb-0">
                                                    <label class="input-label" for="card-information">Card
                                                        Information<span class="text-danger">*</span></label>
                                                    <div class="input-group2">
                                                        <div id="card-element"></div>
                                                    </div>
                                                    <div id="card-errors" class="text-danger my-1"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button :disabled="loader"
                                    class="button button-s2 button-primary button-block button-primary btn-checkout"
                                    @click="payNow">Complete Checkout</button>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-10">
                            <div class="payment-info-sticky px-lg-4 px-md-4 px-0">
                                <div class="payment-info">
                                    <!-- Naveed changes -->
                                    <div class="order-summary-box bg-white rounded shadow-sm">
                                        <h4 class="mb-3 font-weight-bold px-4 pt-4 text-capitalize text-black">your order</h4>
                                        <hr />

                                        <div class="px-4 pt-2 pb-3">
                                            <div class="d-flex justify-content-between fs-14 mb-3 pb-2 border-bottom">
                                                <span class="planeName">{{ pack.title }} Plan</span>
                                                <span>{{ getDisplayPrice(pack) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between fs-14 mb-2 handlingFee border-bottom pb-3" v-if="pack.is_checked && pack.strip_precent">
                                                <span>Handling Fee</span>
                                                <span>${{ getDiscountAmount(pack) }}
                                                    
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between fs-14 mb-2 pb-2 border-bottom">
                                                <span class="planeName">Subtotal</span>
                                                <span>${{ pack.regular_price || pack.price }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between fs-14 mb-3 pb-2 border-bottom position-relative" v-if="discount_amount > 0">
                                                <span class="planeName text-danger">
                                                    Coupon: {{ pack.coupon_title }} ({{ pack.coupon_code }})
                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-danger">
                                                        -${{ pack.price - finalAmount() }}
                                                    </span>
                                                    <button class="ms-1 btn btn-danger btn-sm couponBtn" v-if="discount_amount > 0" @click="resetCoupon">x</button>
                                                </div>
                                            </div>
                                            <div class="mb-3 pb-2 border-bottom" v-if="discount_amount == 0">
                                                <div class="coupon_code d-flex align-items-center">
                                                    <div class="input-group2 my-3">
                                                        <input
                                                            v-model="discount_code"
                                                            type="text"
                                                            class="input-control2 border-0"
                                                            placeholder="Enter coupon code"
                                                            />
                                                    </div>
                                                    <button class="btn btn-primary" @click="applyCoupon">Apply</button>
                                                </div>
                                            </div>
                                            <!-- <div class="text-danger my-1" v-if="errors.discount_code">
                                            </div> -->
                                        </div>

                                        <div class="d-flex justify-content-between font-weight-bold p-4 ldx-total">
                                            <span class="fw-bolder">Total:</span>
                                            <span class="fw-bolder">${{ finalAmount() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-checkout px-2">
                                    <div class="" v-if="tesimonials.length">
                                        <div class="my-4 fst-italic textTestimonial">
                                            "{{ tesimonials[0].text }}"
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <strong>{{ tesimonials[0].author }}</strong>
                                            </div>
                                            <div
                                                class="text-black text-uppercase verifiedLdx d-flex align-items-center">
                                                <img src="/_public_assets/testImg/circle-check-green.svg" width="15"
                                                    class="img-fluid me-1" alt=""> Verified Customer
                                            </div>
                                        </div>
                                        <div class="mt-3 text-warning">
                                            <i v-for="i in tesimonials[0].rating" :key="i" class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />

                                <div class="checkout-achievements px-2">
                                    <ul>
                                        <li>Most Popular WordPress Form Builder </li>
                                        <li>6,000,000+ Active Installations</li>
                                        <li>World Class Support</li>
                                    </ul>
                                </div>
                                <hr class="my-4" />
                                <div class="moneyBackGarantee d-flex align-items-center px-2">
                                    <img src="/_public_assets/testImg/Money-Back-Guarantee-1.svg" width="100"
                                        class="img-fluid" alt="">
                                    <p class="ms-4 mb-0 text-black"><strong>100% Risk-Free Unconditional <br> Money Back
                                            Guarantee!</strong></p>
                                </div>
                            </div>

                            <!-- Naveed changes end -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- <section class="pt-0 pp-section">
                
                <BrandLogos></BrandLogos>
                
            </section> -->
        </template>
        <section class="secureWrapper main-section pb-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="secure_box__wrapper text-center">
                            <div class="feature-box bg-light-pink">
                                <div class="feature-icon-box">
                                    <img src="/_public_assets/testImg/icon2.svg"
                                        alt="User-Friendly Interface" class="feature-icon" />
                                    <h3 class="feature-title">100% Safe and Secure</h3>
                                    <p>Enjoy peace of mind with our anti-spam and form security features.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="secure_box__wrapper text-center">
                            <div class="feature-box bg-light-green-color">
                                <div class="feature-icon-box">
                                    <img src="/_public_assets/testImg/icon1.svg"
                                        alt="User-Friendly Interface" class="feature-icon" />
                                    <h3 class="feature-title">Award-Winning Support</h3>
                                    <p>Our dedicated team is here to assist you with expert advice and quick solutions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="secure_box__wrapper text-center">
                            <div class="feature-box bg-light-blue">
                                <div class="feature-icon-box">
                                    <img src="/_public_assets/testImg/icon 3.svg"
                                        alt="User-Friendly Interface" class="feature-icon" />
                                    <h3 class="feature-title">Satisfaction Guaranteed</h3>
                                    <p>14-day money-back guarantee. No question asked.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section testimonials-section overflow-hidden" id="testimonials">
            <div class="container">
                <div class="row g-4" v-if="tesimonials.length > 0">
                    <!-- START BOX -->
                    <div class="col-lg-4 col-md-4 col-sm-12" v-for="(tesimonial, index) in tesimonials" :key="index">
                        <div class="testimonials-area">
                            <div class="testimonial-item text-center text-lg-start text-md-start">
                                <div class="testimonial-rate">
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 1,
                                        'bi bi-star': tesimonial.rating < 1,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 2,
                                        'bi bi-star': tesimonial.rating < 2,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 3,
                                        'bi bi-star': tesimonial.rating < 3,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating >= 4,
                                        'bi bi-star': tesimonial.rating < 4,
                                    }"></i>
                                    <i :class="{
                                        'bi bi-star-fill': tesimonial.rating == 5,
                                        'bi bi-star': tesimonial.rating < 5,
                                    }"></i>
                                </div>
                                <p>{{ tesimonial.text }}</p>
                                <div class="testimonial-author">
                                    <h4 class="testimonial-author-name">{{ tesimonial.author }}</h4>
                                    <span class="testimonial-author-verified">Verified Customer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END BOX -->
                </div>
            </div>
        </section>
        <section class="main-section faqs-section bg-light-green overflow-hidden pb-250" id="faqs">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="main-head text-center">
                            <h4 class="subheading">Question & Answers</h4>
                            <h2 class="heading">Frequently <span class="text-primary">Asked</span> Questions</h2>
                        </div>
                    </div>
                </div>
                <div class="faqs-area">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div v-for="(faq, index) in faqs" :key="index" class="faq-item" :class="{
                                'show': faq.show,
                                'border-0': index + 1 == (faqs.length)
                            }">
                                <div class="faq-header">
                                    <button class="faq-button" @click.prevent="faqsToggle(index)">
                                        <h3>{{ faq.question }}</h3>
                                        <span class="faq-toggle-icon">
                                            <i :class="faq.show ? 'bi bi-chevron-down' : 'bi bi-chevron-right'"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="faq-body">
                                    <div class="faq-content">
                                        <p>{{ faq.answer }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MasterLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import MasterLayout from "@/frontend/Layouts/MasterLayout.vue";
import BrandLogos from "@/frontend/Components/BrandLogos.vue";
import { loadStripe } from "@stripe/stripe-js/pure";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    components: {
        Head,
        Link,
        MasterLayout,
        BrandLogos,
    },

    data() {
        return {
            urlParams: new URLSearchParams(window.location.search),
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            packages: [],
            pack: {
                id: null,
                price: 0,
                regular_price: 0,
                is_checked: false,
                strip_precent: 0,
                coupon_title: '',
                coupon_code: '',
            },
            stripe: false,
            cardElement: false,
            paymentMethodId: "",
            email: "",
            password: "",
            // fullname: "",
            first_name: "",
            last_name: "",
            phone_number: "",
            websites: [
                {
                    website_name: "",
                    website_url: "",
                },
            ],
            card_holder_name: "",
            discount_code: "",
            discount_amount: 0,
            discount_type: '',
            coupon_error: '',
            coupon_success: '',

            faqs: [
                {
                    question: 'Who is ' + this.$page.props.app.name + ' for?',
                    answer:
                        '' + this.$page.props.app.name + ' is ideal for business owners, bloggers, designers, developers, photographers, and anyone looking to create custom WordPress forms.',
                    show: false,
                },
                {
                    question: 'Do I need coding skills to use ' + this.$page.props.app.name + '?',
                    answer:
                        'No, ' + this.$page.props.app.name + ' is designed to be user-friendly for anyone, regardless of coding skills.',
                    show: false,
                },
                {
                    question: 'What are the requirements to use ' + this.$page.props.app.name + '?',
                    answer:
                        'You need a WordPress site and basic knowledge of form creation.',
                    show: false,
                },
                {
                    question: 'Is ' + this.$page.props.app.name + ' translation-ready?',
                    answer:
                        'Yes, ' + this.$page.props.app.name + ' supports multiple languages and is fully translation-ready.',
                    show: false,
                },
                {
                    question: 'Will ' + this.$page.props.app.name + ' affect my website’s speed?',
                    answer:
                        '' + this.$page.props.app.name + ' is optimized for performance and will not affect your website’s speed.',
                    show: false,
                }
            ],
            tesimonials: [
                {
                    rating: 5,
                    text: "Since we switched to LeadxForm, we’ve noticed a big improvement in both the number and quality of leads coming in. The conditional logic keeps things relevant for each user, and it’s really helped us qualify leads more effectively.",
                    author: "-James R",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Thanks to LeadxForm, which has made collecting leads so much easier. We have already seen a noticeable increase in conversions just by improving our form flow.",
                    author: "-Sarah M",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We would definitely accept that this platform has streamlined our client intake process. What used to take hours to sort through is now happening in real time.",
                    author: "-Ben T",
                    verified: true
                }
            ],
            computed: {
                selectedTestimonial() {
                    return this.testimonials[0];
                }
            },
            errors: {},
            isRevealPassword: false,
            loader: false,


        };
    },

    methods: {
        // naveed changes

        async applyCoupon() {
            this.coupon_error = '';
            this.coupon_success = '';
            this.discount_amount = 0;
            this.discount_type = '';

            if (!this.discount_code) {
                toast.error("Please enter a coupon code.");
                return;
            }

            try {
                const response = await axios.post(route('api.coupon.validate'), {
                code: this.discount_code,
                package_id: this.pack.id,
            });

            if (response.data.success) {
                const { discount, discount_type, title, code, original_price } = response.data;
                this.discount_amount = discount;
                this.discount_type = discount_type;
                this.coupon_success = response.data.message;

                this.pack.coupon_discount = discount;
                this.pack.discount_type = discount_type;
                this.pack.coupon_title = title;
                this.pack.coupon_code = code;
                this.pack.original_price = original_price;

                toast.success(response.data.message);
            } else {
                toast.error(response.data.message || "Coupon invalid.");
                // this.coupon_error = response.data.message || "Coupon invalid.";
            }
        } catch (error) {
            // this.coupon_error =
            // error?.response?.data?.message || "Something went wrong!";
                toast.error(error?.response?.data?.message || "Something went wrong!");
            }
        },

        resetCoupon() {
            this.discount_code = "";
            this.discount_amount = 0;
            this.discount_type = "";
            this.coupon_success = "";
        },

        getDisplayPrice(item) {
            const price = Number(item.regular_price || item.price || 0);
            const discount = item.is_checked && item.strip_precent
                ? (price * item.strip_precent) / 100
                : 0;

            const final = price - discount;

            return this.priceFormat(Math.round(final));
        },

        getDiscountAmount(item) {
            if (!item || typeof item !== 'object') return "0";

            const price = Number(item.regular_price || item.price || 0);
            const percent = Number(item.strip_precent || 0);

            if (!item.is_checked || !percent) return "0";

            const discount = (price * percent) / 100;
            return Math.round(discount);
        },

        faqsToggle(index) {
            this.faqs[index].show = !this.faqs[index].show;
        },

        discountAmount() {
            if (!this.pack || !this.discount_type || !this.discount_amount) return 0;

            const price = Number(this.pack.price);

            if (this.discount_type === 'percent') {
                return (price * this.discount_amount) / 100;
            } else if (this.discount_type === 'fixed') {
                return this.discount_amount;
            }
            return 0;
        },

        discountLabel() {
            return "50% Off";
        },
        finalAmount() {
            let base = Number(this.pack.price);
            let discount = this.discountAmount();
            return (base - discount).toFixed(2);
        },
        // naveed changes end

        revealPassword() {
            this.isRevealPassword = this.isRevealPassword ? false : true;
        },

        // handlingFee() {
        //     let price = Number(this.pack.price) || 0;
        //     return price > 0 ? (price * 0.1).toFixed(2) : "0.00";
        // },
        totalAmount() {
            let total = Number(this.pack.price);
            return this.$page.props.currency_symbol + total.toFixed(2);
        },
        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(0);
            return symbol ? this.$page.props.currency_symbol + price : price;
        },

        dateFormat(date, format, cformat = null) {
            if (cformat) {
                return moment(date, cformat).format(format);
            }

            return moment(date).format(format);
        },

        async getPackages() {
            await axios
                .get(route("api.guest.get.packages"), {
                    params: {
                        orderby: "sort",
                        order: "ASC",
                        status: "active",
                        limit: 3,
                    },
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    let $response = response.data;
                    this.packages = $response.data;
                    if (!this.user) {
                        if (this.urlParams.get("plan")) {
                            if (this.packages.length) {
                                this.packages.forEach((item) => {
                                    if (item.id == this.urlParams.get("plan")) {
                                        this.initPayment(item);
                                    }
                                });
                            }
                        }
                    }
                })
                .catch((error) => {
                    console.log(error.response.data.message);
                });
        },

        addWebsite() {
            if (this.pack != "") {
                if (this.pack.website_limit == null) {
                    this.websites.push({
                        website_name: "",
                        website_url: "",
                    });
                } else {
                    if (this.websites.length < this.pack.website_limit) {
                        this.websites.push({
                            website_name: "",
                            website_url: "",
                        });
                    }
                }
            }
        },

        async initPayment(pack) {
            if (this.pack == pack) {
                this.pack = "";
                return;
            }

            this.errors = {};
            this.pack = pack;
            setTimeout(function () {
                if ($("#choose-websites").length) {
                    $("html, body").animate(
                        { scrollTop: $("#choose-websites").offset().top - 120 },
                        0
                    );
                }
            }, 100);

            if (!this.pack.free_plan) {
                this.stripe = await loadStripe(this.$page.props.stripe_key);
                this.elements = this.stripe.elements();
                this.cardElement = this.elements.create("card", {
                    style: {
                        base: {
                            color: "#000000",
                            fontSize: "16px",
                            "::placeholder": {
                                color: "#000000",
                            },
                        },
                    },
                });
                this.cardElement.mount("#card-element");
                this.cardElement.addEventListener("change", function (event) {
                    const displayError = document.getElementById("card-errors");
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = "";
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
                // fullname: this.fullname,
                first_name: this.first_name,
                last_name: this.last_name,
                phone_number: this.phone_number,
                package: this.pack.id,
                websites: this.websites,
                card_holder_name: this.card_holder_name,
                payment_method: "stripe",
            };

            await axios
                .post(route("api.guest.validate.subscription"), form, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    let $response = response.data;
                    if ($response.error == 0) {
                        if (!this.pack.free_plan) {
                            this.create_payment_method();
                        } else {
                            this.createSubscription();
                        }
                    } else {
                        toast.error($response.message);
                    }
                })
                .catch((error) => {
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
            this.stripe
                .createPaymentMethod({
                    type: "card",
                    card: this.cardElement,
                    billing_details: {
                        name: this.card_holder_name,
                    },
                })
                .then((result) => {
                    if (result.error) {
                        var errorElement =
                            document.getElementById("card-errors");
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
                fullname: this.first_name + ' ' + this.last_name,
                first_name: this.first_name,
                last_name: this.last_name,
                phone_number: this.phone_number,
                package: this.pack.id,
                websites: this.websites,
                card_holder_name: this.card_holder_name,
                payment_method: "stripe",
                paymentMethodId: this.paymentMethodId,
                discount_code: this.discount_code,
            };

            await axios
                .post(route("api.guest.create.subscription"), form, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                })
                .then((response) => {
                    let $response = response.data;
                    this.$cookies.set("lxf-success-msg", $response.message, 60);
                    this.$cookies.set(
                        "lxf-token",
                        $response.data.authorisation.token,
                        $response.data.authorisation.expiration
                    );
                    this.$cookies.set(
                        "lxf-user",
                        $response.data.user,
                        $response.data.authorisation.expiration
                    );

                    window.location.href = route(
                        "app.customer.subscription.index"
                    );
                    this.loader = false;
                })
                .catch((error) => {
                    this.loader = false;
                    if (error.response.status == 422) {
                        for (const key in error.response.data.data) {
                            this.errors[key] = error.response.data.data[key][0];
                        }
                    }

                    toast.error(error.response.data.message);
                });
        },
    },

    mounted() {
        const queryString = window.location.search;

        if (queryString && window.location.pathname === '/pricing') {
            window.location.href = '/pricing';
        }
    },

    created() {
        this.getPackages();
    },
};
</script>
