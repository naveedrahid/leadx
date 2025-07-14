<template>
    <Head title="Features" />
    <MasterLayout :loader="false">
        <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="hero-content text-center">
                            <!-- <div class="subheading h4">{{ $page.props.app.name }} Features</div> -->
                            <h1><span class="text-primary">{{ $page.props.app.name }}</span> Features</h1>
                                <p class="py-4">Explore how LeadXForms delivers powerful, user-friendly tools to build smarter WordPress forms. From essential features to advanced integrations, everything you need is built in to streamline your workflow and boost conversions.</p>
                            <Link :href="route('pricing')" class="button button-s2 button-primary button-primary">Get {{ $page.props.app.name }} Now →</Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section features-section overflow-hidden pb-250" id="features">
            <div class="container">
                <!-- <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="main-head text-center">
                            <h4 class="subheading">{{ $page.props.app.name }} Features</h4>
                            <h2 class="heading">WordPress <span class="text-primary">{{ $page.props.app.name }}</span>
                                Plugin that's Easy to Create</h2>
                        </div>
                    </div>
                </div> -->
                <div class="features-area" v-if="features.length > 0">
                    <div class="row g-4">
                        <div v-for="(feature, index) in features" :key="index" class="col-lg-4 col-md-6 equal-height">
                            <div class="feature-box">
                                <div class="feature-icon-box">
                                    <img :src="feature.image" :alt="feature.title" class="feature-icon">
                                    <h3 class="feature-title">{{ feature.title }}</h3>
                                    <p>{{ feature.description }}</p>
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

export default {
    components: {
        Head,
        Link,
        MasterLayout
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
             features: [
                {
                    title: 'User-Friendly Interface',
                    image: '/_public_assets/img/features-icons/user-friendly-interface.png',
                    description: this.$page.props.app.name + '  is fully responsive and works seamlessly across mobile, tablet, and desktop devices.'
                },
                {
                    title: 'Entry Management',
                    image: '/_public_assets/img/features-icons/entry-management.png',
                    description: 'Easily view, track, manage, and export all form submissions from one centralized dashboard.'
                },
                {
                    title: 'Easy to Embed',
                    image: '/_public_assets/img/features-icons/easy-to-embed.png',
                    description: 'Instantly add your forms to pages, blog posts, sidebars, footers, and more with just a few clicks.'
                },
                {
                    title: 'Customizable Forms',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'Create fully customizable forms that match your brand and needs - no coding required.'
                },
                {
                    title: '2000+ Form Tamplates',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'Save time and streamline your workflow with our ready-to-use form templates.'
                },
                {
                    title: 'Styled Email Templates',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'Send beautifully formatted emails for each form response.'
                },
                {
                    title: 'Smart Spam Filters',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'LeadXForms uses smart spam filters to block bots and junk effortlessly, ensuring only genuine submissions reach your inbox.'
                },
                
                {
                    title: 'Email Notifications',
                    image: '/_public_assets/img/features-icons/instant-notifications.png',
                    description: 'With our fast form notification function for your team, respond to leads promptly.'
                },
                {
                    title: 'Custom Redirection',
                    image: '/_public_assets/img/features-icons/customizable-forms.png',
                    description: 'Send users to a thank-you page or a custom URL after submission.'
                },
                {
                    title: 'SMTP Integration',
                    image: '/_public_assets/img/features-icons/easy-to-embed.png',
                    description: 'Ensure reliable email delivery by connecting your SMTP provider.'
                },
                
                {
                    title: 'Form Security',
                    image: '/_public_assets/img/features-icons/easy-to-embed.png',
                    description: 'Your data is protected with built-in spam filters, IP blocking, and CAPTCHA—so you get real leads, not bots.'
                },
                {
                    title: 'Spam Protection',
                    image: '/_public_assets/img/features-icons/spam-protection.png',
                    description: 'Use Google reCAPTCHA, block specific keywords, and even block IPs.'
                },
            ],
            errors: {},
            loader: false
        };
    },

};
</script>
