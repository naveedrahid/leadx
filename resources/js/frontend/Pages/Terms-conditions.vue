<template>

    <Head title="Terms and Conditions" />
    <MasterLayout :loader="false">
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
        <section class="main-section contact-section overflow-hidden pb-250" id="contact">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-12 col-md-9">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-lg-10 order-lg-last">
                                <div class="contact-content">
                                    <div class="main-head main-head2">
                                        <!-- <h2 class="heading">Terms and Conditions</h2> -->
                                        <h2 class="heading">Welcome to LeadxForm!</h2>
                                        <p>Please take a moment to read through the terms and conditions on this page before using our website, services, or software platform. By accessing or using LeadxForms (referred to as “we,” “our,” or “us”), you’re agreeing to the terms outlined here. If you don’t agree with any part of them, it’s best not to use the site.</p>
                                        <h2 class="heading mt-4">Use of the Platform </h2>
                                        <p>LeadxForm is a platform that helps businesses and individuals build smart and effective forms that generate leads. Our forms require no coding or use of complex navigation. You can use our services to create lead forms or contact forms that help you collect the right information from your audience. </p>
                                        <p><strong><em>By using our site, you agree not to:</em></strong></p>
                                        <ul style="list-style:disc;">
                                            <li>Violate any applicable law or regulation.</li>
                                            <li>Attempt to disrupt the performance, security, or integrity of our systems.</li>
                                            <li>Use the platform to transmit or collect any harmful, unauthorised, or illegal data.</li>
                                        </ul>
                                        <p>In the event of misconduct, we reserve the right to investigate and take appropriate actions. </p>
                                        <h2 class="heading mt-4">Account Registration</h2>
                                        <p>To use most of the features available on LeadxForm, you’ll first need to set up an account. We expect that you provide accurate and up-to-date details during the sign-up process, as this helps us deliver a secure and reliable service.</p>
                                        <p>Once your account is created, you’re responsible for keeping your login credentials private. Please avoid sharing your password with anyone else. If you ever suspect your account has been accessed without permission, let us know straightaway so we can help secure it.</p>
                                        <p>All activity that takes place under your account is your responsibility. If the account is used in a way that breaches our terms, appears to be fraudulent, or is left inactive for an extended period, we may suspend or close it. Wherever reasonable, we’ll try to get in touch with you first before taking action.</p>
                                        <h2 class="heading mt-4">Intellectual Property Rights</h2>
                                        <p>We own the LeadxForm platform, including the design, the code, the branding, and all the features that come with it. You are welcome to use the features while your subscription is active, but you can’t copy it, sell it, or claim it as your property. </p>
                                        <p>By using our platform, you are given a non-exclusive, non-transferrable licence to use our tools. But you do not have the right to modify or resell our platform or its components without written consent. </p>
                                        <p>You retain the ownership of any forms, content, or data you create using LeadxForms. We do not claim ownership of your content, but by using our service, you grant us the right to process and host that content solely for the purpose of providing the service to you.</p>
                                        <h2 class="heading mt-4">Using Third-Party Tools</h2>
                                        <p>LeadxForms allows you to connect with a range of third-party platforms, such as CRMs, email marketing tools, and analytics services. It is to help you get more from your forms. </p>
                                        <p>When you choose to enable one of these integrations, you are permitting us to share the necessary data with that service so the connection works properly. What happens to your data once it reaches the third party is governed by their terms and privacy policies, not ours. </p>
                                        <p>We encourage you to go over the terms and policies, as we can’t take responsibility for how third-party tools handle your data. </p>
                                        <h2 class="heading mt-4">Privacy & Data Handling </h2>
                                        <p>Your privacy is important to us. When you use LeadxForm, we store and collect information so we can deliver our services and improve how our platform works. It is advised to read our privacy policy, which outlines our approach to how we protect your data from being misused. We use industry-standard security practices to keep your information safe and give you the tools to manage or delete your data when needed.</p>
                                        <h2 class="heading mt-4">Limits on Our Responsibility </h2>
                                        <p>We work hard to keep LeadxForms running smoothly and reliably, but like any software, it may not always be free from errors or perfectly aligned with every individual expectation.</p>
                                        <p>To the extent permitted by law, we are not liable for:</p>
                                        <ul>
                                            <li>Indirect or consequential losses, including lost revenue or data.</li>
                                            <li>Damages resulting from interruptions, third-party integrations, or user misuse.</li>
                                            <li>Any claims exceeding the total amount paid by you to LeadxForms in the 12 months preceding the incident.</li>
                                        </ul>
                                        <h2 class="heading mt-4">Changes to These Terms </h2>
                                        <p>We may update these terms and conditions from time to time to reflect changes on our platform. When we do, we will revise the effective date at the bottom of this page. </p>
                                        <p>We recommend you  review these terms and conditions regularly. Continued use of LeadxForm after the changes are published demonstrates your acceptance of the updated terms. </p>
                                        <h2 class="heading mt-4">Need Further Help?</h2>
                                        <p>If you have any more questions or queries, our support team is here to assist. We ensure that your experience with LeadxForm remains professional and secure. </p>
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
import { Head } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';

export default {
    components: {
        Head,
        MasterLayout
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
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
    }
};
</script>
