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
                                        <h2 class="heading">Terms and Conditions</h2>
                                        <p>Our professional support team is here to help you:</p>
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
