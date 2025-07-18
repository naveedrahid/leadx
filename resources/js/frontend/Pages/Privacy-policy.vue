<template>

    <Head title="Privacy Policy">
        <meta name="description"
            content="Learn how LeadxForms collects, uses, and protects your personal information. Review our Australian-based privacy practices, including data handling and security" />
    </Head>
    <MasterLayout :loader="false">
        <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="hero-content text-center">
                            <h1>Privacy Policy</h1>
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
                                        <!-- <h2 class="heading">Privacy Policy</h2> -->
                                        <p>Leadxform is committed to protecting your privacy and handling your personal information responsibly. This privacy policy explains how we collect, use, store, and disclose your personal information when you use our site or services. Please note that we adhere to the Privacy Act 1988 (Cth) and Australian privacy principles to ensure the security of your data.</p>
                                        <p>By accessing LeadxForms or using any of our services, you agree to the privacy practices outlined in this policy.</p>
                                        <h2 class="heading mt-4">Who We Are</h2>
                                        <p>LeadxForm is an Australian company based in Brisbane. We provide our customers with a lead-building form that helps them create, customise, and manage forms. It helps them capture data, manage data collection, and improve user interaction. </p>
                                        <h2 class="heading mt-4">Information We Collect</h2>
                                        <p><strong>Information You Provide</strong></p>
                                        <ul style="list-style: disc;">
                                            <li>Your name, email address, and company details</li>
                                            <li>Payment & billing information</li>
                                            <li>Support requests, feedback, or direct messages</li>
                                        </ul>
                                        <p><strong>Information Collected Automatically</strong></p>
                                        <ul style="list-style: disc;">
                                            <li>Device type, browser, IP address, and general location</li>
                                            <li>Cookies and tracking technologies</li>
                                            <li>Usage data (e.g. pages visited, time spent on site, form activity)</li>
                                        </ul>
                                        <p>When someone fills out the form you have created using LeadxForm, that data belongs to you as the form creator. We store it securely on your behalf and do not access or use it unless you permit us to do so. </p>
                                        <h2 class="heading mt-4">Use of Personal Information</h2>
                                        <ul style="list-style: disc;">
                                            <li>Create and manage your account.</li>
                                            <li>Deliver and improve the functionality of our platform.</li>
                                            <li>Process payments and manage subscriptions.</li>
                                            <li>Respond to support enquiries and provide customer service.</li>
                                            <li>Send service-related updates, product announcements, or changes to our policies.</li>
                                            <li>Monitor usage patterns and troubleshoot technical issues.</li>
                                            <li>Comply with our legal obligations.</li>
                                        </ul>
                                        <h2 class="heading mt-4">Disclosure of Personal Information</h2>
                                        <p>We do not sell your personal information or form data to third parties. However, we may share the data with the following entities to proceed with the operations lawfully.</p>
                                        <ul style="list-style: disc;">
                                            <li>Trusted third-party service providers who support our platform (e.g., hosting, analytics, payment processing)</li>
                                            <li>Legal or regulatory authorities, when disclosure is necessary to meet legal obligations or to safeguard our legal interests.</li>
                                            <li>A purchaser or successor in the event of a business transfer, merger, or acquisition</li>
                                        </ul>
                                        <p>All the service providers we work with are required to handle your data securely and in accordance with the privacy regulations established by the relevant regulatory bodies. </p>
                                        <h2 class="heading mt-4">Data Storage & Security </h2>
                                        <p>LeadxForm uses secure services and industry-standard encryption to protect your information. However, no method of electronic storage or transmission can be 100% safe. We take appropriate steps to protect your data, but we cannot guarantee absolute security.</p>
                                        <p>Any transmission or exchange of information is carried out at your own risk. While we take measures to safeguard your data from authorised access, modification, and disclosure, we cannot assure the security in line with this privacy policy. </p>
                                        <h2 class="heading mt-4">Third-Party Links </h2>
                                        <p>You may come across links on our site that lead to external websites. We do not control these sites or services; therefore, we are not responsible for the privacy practices or content on these third-party sites.</p>
                                        <p>We encourage you to always review their privacy policies and terms before proceeding and sharing your personal information. </p>
                                        <h2 class="heading mt-4">Changes to the Policy </h2>
                                        <p>Our privacy policy may be updated from time to time. If there are any major changes, we will make every reasonable effort to inform you by email.</p>
                                        <p>Please take time to review this policy regularly so you stay informed about how your data is handled. </p>
                                        <h2 class="heading mt-4">Get in Touch </h2>
                                        <p>If you have any questions about this privacy policy or the way we manage your data, you’re welcome to get in touch. Our team is here to guide you and will respond as promptly as possible.</p>
                                        <!-- <p><strong>Phone:</strong></p> -->
                                        <p class="mt-4"><strong>Support LeadX:</strong> <a href="mailto:support@leadxforms.com">support@leadxforms.com</a></p>
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
import { Head } from "@inertiajs/vue3";
import MasterLayout from "@/frontend/Layouts/MasterLayout.vue";

export default {
    components: {
        Head,
        MasterLayout,
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            errors: {},
            loader: false,
        };
    },
};
</script>
