<template>
<Head title="Home" />
<MasterLayout :loader="loader">
    <section class="hero-section overflow-hidden" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="hero-content">
                        <h1>Welcome To {{ $page.props.app.name }}</h1>
                        <div class="row">
                            <div class="col-lg-9">
                                <p>The Unlimited solution for creating custom forms and flows to connect users and enhance engagement and broaden your online presence.</p>
                                <div class="mt-5">
                                    <a href="#pricing" class="button button-s2 button-primary">Buy Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="hero-image">
                        <img src="/_public_assets/img/welcome-to-leadxforms.png" :alt="'Welcome To' + $page.props.app.name" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- <section class="hero-section overflow-hidden" id="hero">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="hero__wrapper text-center">
                        <div class="hero__info__box">
                            <span>UltimateAI</span>
                            <span>#1 ai text to audio platform</span>
                        </div>
                        <div class="hero-content">
                            <h1>Welcome To {{ $page.props.app.name }}</h1>
                            <p>The Unlimited solution for creating custom forms and flows to connect users and enhance engagement and broaden your online presence.</p>
                            <div class="mt-5">
                                <a href="#pricing" class="button button-s2 button-primary me-lg-2 me-xl-2 me-md-2 me-0">Buy Now</a>
                                <a href="javascript:;" class="button button-s2  button-outline ms-lg-2 ms-xl-2 ms-md-2 ms-0">Explore All Tools</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="hero-image mt-5 pt-5">
                        <img src="/_app_assets/images/banner/tts.png" :alt="'Welcome To' + $page.props.app.name" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- BEGIN::BRAND LOGOs COMPONENT -->
    <BrandLogos></BrandLogos>
    <!-- END::BRAND LOGOs COMPONENT -->

    <section class="main-section features-section overflow-hidden" id="features">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="main-head text-center">
                        <h4 class="subheading">{{ $page.props.app.name }} Features</h4>
                        <h2 class="heading">WordPress <span class="text-primary">{{ $page.props.app.name }}</span> Plugin that's Easy to Create</h2>
                    </div>
                </div>
            </div>
            <div class="features-area" v-if="features.length>0">
                <div class="row g-2">
                    <div v-for="(feature, index) in features" :key="index" class="col-lg-4 col-md-6 equal-height">
                        <div class="feature-box">
                            <div class="feature-icon-box">
                                <img :src="feature.image" :alt="feature.title" class="feature-icon">
                                <h3 class="feature-title">{{ feature.title }}</h3>
                            </div>
                            <p>{{ feature.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-section testimonials-section bg-light overflow-hidden" id="testimonials">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="main-head text-center">
                        <h4 class="subheading">Testimonials</h4>
                        <h2 class="heading">Our <span class="text-primary">Customer</span> says</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-8">
                    <div class="testimonials-area" v-if="tesimonials.length>0">
                        <carousel v-bind="tesimonials_slider_settings" :breakpoints="tesimonials_slider_breakpoints">
                            <slide class="testimonial-slide" v-for="(tesimonial, index) in tesimonials" :key="index">
                                <div class="testimonial-item">
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
                                        <span class="testimonial-author-verified" v-if="tesimonial.verified">Verified Customer</span>
                                    </div>
                                </div>
                            </slide>

                            <template #addons>
                                <div class="testimonials-pagination">
                                    <pagination />
                                </div>
                            </template>
                        </carousel>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-section whychooseus-section bg-light-green overflow-hidden" id="whychooseus">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="main-head text-center">
                        <h4 class="subheading">Why Choose Us</h4>
                        <h2 class="heading mb-4">Top 7 Reasons Why Users Love <span class="text-primary">{{ $page.props.app.name }}</span></h2>
                        <p>WPForms is the ultimate WordPress form solution for YOU. Discover why over 6,000,000 savvy business owners, designers, and developers trust and love WPForms, and why you will too!</p>
                    </div>
                </div>
            </div>
            <div class="whychooseus-area">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-5 col-md-5">
                        <div class="whychooseus-image">
                            <img src="/_public_assets/img/why-choose-us.png" alt="Why Choose Us" class="img-fluid rounded">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="whychooseus-content">
                            <ul class="whychooseus-list">
                                <li>Create Custom Online Forms in Minutes, Not Hours</li>
                                <li>Seamlessly Connect with your favorite apps</li>
                                <li>Advance WordPress form features at a great value</li>
                            </ul>
                            <a href="#pricing" class="button button-s2 button-primary">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-section pricing-section overflow-hidden" id="pricing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="main-head text-center">
                        <h2 class="heading">Check Out <span class="text-primary">Pricing</span></h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-8 col-sm-10">
                    <div class="pricing-area">
                        <template v-if="packages.length>0">
                            <div class="row">
                                <template v-for="(item, index) in packages" :key="index">
                                    <div class="col-lg-4">
                                        <div class="pricing-box" :class="{
                                            'pricing-box-featured': item.recommended
                                        }">
                                            <span v-if="item.recommended" class="pricing-box-featured_label">Featured Plan</span>
                                            <div class="pricing-box-head">
                                                <h3 class="pricing-box-title">{{ item.title }}</h3>
                                                <div class="pricing-box-price">
                                                    <div class="pricing-box-amount">{{ priceFormat(item.price) }}</div>
                                                    <span class="pricing-box-duration">/ {{ item.duration_lifetime ? 'Lifetime' : item.format_duration }}</span>
                                                </div>
                                                <Link v-if="!user" :href="`${route('pricing')}?plan=${item.id}`" class="button button-s2 button-primary button-block">Buy Now</Link>
                                                <Link v-else-if="user.user_type === 'customer'" :href="route('app.customer.subscription.billing')" class="button button-s2 button-primary button-block">Upgrade</Link>
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
                        </template>
                        <template v-else>
                            <div class="notfound">No Packages Found</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-section blog-section bg-light overflow-hidden" id="blog" style="display:none">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="main-head text-center">
                        <h2 class="heading"><span class="text-primary">{{ $page.props.app.name }}</span> Blog & Resources</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-8">
                    <div class="blog-area">
                        <div class="blog-posts-grid">
                            <div class="row">
                                <div class="col-lg-4">
                                    <article class="blog-post">
                                        <a href="#" class="blog-post-img">
                                            <img src="/_public_assets/img/dummy.png" alt="Top 5 Tips for Creating High-Converting WordPress Forms" class="img-fluid">
                                        </a>
                                        <div class="blog-post-content">
                                            <h3><a href="#">Top 5 Tips for Creating High-Converting WordPress Forms</a></h3>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-4">
                                    <article class="blog-post">
                                        <a href="#" class="blog-post-img">
                                            <img src="/_public_assets/img/dummy.png" alt="Why Every Business Needs a Custom WordPress Form Builder" class="img-fluid">
                                        </a>
                                        <div class="blog-post-content">
                                            <h3><a href="#">Why Every Business Needs a Custom WordPress Form Builder</a></h3>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-lg-4">
                                    <article class="blog-post">
                                        <a href="#" class="blog-post-img">
                                            <img src="/_public_assets/img/dummy.png" alt="The Future of Online Forms: Trends to Watch in 2024" class="img-fluid">
                                        </a>
                                        <div class="blog-post-content">
                                            <h3><a href="#">The Future of Online Forms: Trends to Watch in 2024</a></h3>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-section faqs-section bg-light-green overflow-hidden" id="faqs">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="main-head text-center">
                        <h4 class="subheading">Question & Answers</h4>
                        <h2 class="heading">Frequently Asked Questions</h2>
                    </div>
                </div>
            </div>
            <div class="faqs-area">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div v-for="(faq, index) in faqs" :key="index" class="faq-item" :class="{
                            'show': faq.show,
                            'border-0': index+1 == (faqs.length)
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
    <section class="main-section contact-section overflow-hidden" id="contact">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-12 col-md-9">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-6 order-lg-last">
                            <div class="contact-content">
                                <div class="main-head main-head2">
                                    <h2 class="heading">Contact Us</h2>
                                    <p>Our professional support team is here to help you:</p>
                                </div>
                                <div class="contact-form-wrap">
                                    <form @submit.prevent="sendFeedBack()" class="contact-form">
                                        <div class="row gx-3">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" v-model="contact_form.name" class="input-control" placeholder="Name">
                                                    <div class="text-danger" v-if="errors.name">
                                                        <small>{{ errors.name }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="email" v-model="contact_form.email" class="input-control" placeholder="Email">
                                                    <div class="text-danger" v-if="errors.email">
                                                        <small>{{ errors.email }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="text" v-model="contact_form.subject" class="input-control" placeholder="Subject">
                                                    <div class="text-danger" v-if="errors.subject">
                                                        <small>{{ errors.subject }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <textarea v-model="contact_form.message"  class="input-control" placeholder="Leave a Message"></textarea>
                                                    <div class="text-danger" v-if="errors.message">
                                                        <small>{{ errors.message }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="contact-formbtn-group">
                                                    <button class="button button-primary" :disabled="contactFormLoader">
                                                        <template v-if="contactFormLoader">
                                                            <i class="bi bi-arrow-clockwise spin"></i> Sending...
                                                        </template>
                                                        <template v-else>
                                                            <i class="bi bi-send"></i> Send Message
                                                        </template>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 order-lg-first">
                            <div class="contact-img">
                                <img src="/_public_assets/img/contact-us.png" alt="Contact Us" class="img-fluid">
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
import { Head, Link } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';
import BrandLogos from '@/frontend/Components/BrandLogos.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

export default {
    components: {
        Head,
        Link,
        Carousel,
        Slide,
        Pagination,
        Navigation,
        MasterLayout,
        BrandLogos
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            features: [
                {
                    title: 'User-Friendly Interface',
                    image: '/_public_assets/img/features-icons/user-friendly-interface.png',
                    description: this.$page.props.app.name + ' is fully mobile, tablet, and desktop responsive.'
                },
                {
                    title: 'Entry Management',
                    image: '/_public_assets/img/features-icons/entry-management.png',
                    description: 'For a more efficient workflow, view all of your leads in one location.'
                },
                {
                    title: 'Spam Protection',
                    image: '/_public_assets/img/features-icons/spam-protection.png',
                    description: 'Spam submissions are immediately stopped by our clever captcha and anti-spam technology.'
                },
                {
                    title: 'Easy to Embed',
                    image: '/_public_assets/img/features-icons/easy-to-embed.png',
                    description: 'You may quickly insert your forms into blog posts, web pages, sidebar widgets, the footer, etc.'
                },
                {
                    title: 'Instant Notifications',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'With our fast form notification function for your team, respond to leads promptly.'
                },
                {
                    title: 'Customizable Forms',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'By adding and organizing fields as needed, you can construct forms that are highly customized. Text, email, checkboxes, and other common field kinds include.'
                },
            ],
            tesimonials: [
                {
                    rating: 5,
                    text: "LeadX has completely transformed how we manage our customer interactions. The intuitive form builder made it easy to create custom forms that fit our needs perfectly. Our lead generation has increased by 40% since we started using LeadX!",
                    author: "Sarah J.",
                    verified: true
                },
                {
                    rating: 5,
                    text: "Thanks to LeadX, our workflow has become much more efficient. The pre-built form templates saved us a ton of time, and the mobile-friendly forms ensure our clients can reach us from any device. Truly a fantastic tool!",
                    author: "Emily R.",
                    verified: true
                },
                {
                    rating: 5,
                    text: "We've been using LeadX for a few months now, and it's been a game-changer for our business. The smart conditional logic feature allows us to personalize our forms, leading to higher engagement and better data collection. Highly recommend!",
                    author: "John D.",
                    verified: true
                }
            ],
            tesimonials_slider_settings: {
                itemsToShow: 3,
                autoplay: 5000,
                wrapAround: true
            },
            tesimonials_slider_breakpoints: {
                360: {
                    itemsToShow: 1,
                },
                992: {
                    itemsToShow: 3,
                }
            },
            faqs: [
                {
                    question: 'Who is '+ this.$page.props.app.name +' for?',
                    answer:
                        ''+ this.$page.props.app.name +' is ideal for business owners, bloggers, designers, developers, photographers, and anyone looking to create custom WordPress forms.',
                    show: false,
                },
                {
                    question: 'Do I need coding skills to use '+ this.$page.props.app.name +'?',
                    answer:
                        'No, '+ this.$page.props.app.name +' is designed to be user-friendly for anyone, regardless of coding skills.',
                    show: false,
                },
                {
                    question: 'What are the requirements to use '+ this.$page.props.app.name +'?',
                    answer:
                        'You need a WordPress site and basic knowledge of form creation.',
                    show: false,
                },
                {
                    question: 'Is '+ this.$page.props.app.name +' translation-ready?',
                    answer:
                        'Yes, '+ this.$page.props.app.name +' supports multiple languages and is fully translation-ready.',
                    show: false,
                },
                {
                    question: 'Will '+ this.$page.props.app.name +' affect my website’s speed?',
                    answer:
                        ''+ this.$page.props.app.name +' is optimized for performance and will not affect your website’s speed.',
                    show: false,
                }
            ],
            packages: [],
            contact_form: {
                name: '',
                email: '',
                subject: '',
                message: ''
            },
            errors: {},
            contactFormLoader: false,
            loader: false
        };
    },

    methods: {
        priceFormat(price, symbol = true) {
            price = parseInt(price).toFixed(0);
            return symbol ? this.$page.props.currency_symbol + price : price;
        },

        faqsToggle(index) {
            this.faqs[index].show = !this.faqs[index].show;
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
            }).catch((error) => {
                console.log(error.response.data.message);
            });
        },

        async sendFeedBack() {
            this.contactFormLoader = true;
            this.errors = {};
            await axios.post(route('api.guest.create.feedback'), this.contact_form, {
                headers: {
                    "Content-Type": "application/json"
                },
            }).then((response) => {
                this.contactFormLoader = false;
                let $response = response.data;
                this.contact_form.name = '';
                this.contact_form.subject = '';
                this.contact_form.email = '';
                this.contact_form.message = '';
                toast.success($response.message);
            }).catch((error) => {
                this.contactFormLoader = false;
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
