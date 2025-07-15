<template>

    <Head title="Contact Us" />
    <MasterLayout :loader="loader">
        <section class="hero-section main-section overflow-hidden bg-light-green" id="hero">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-10 col-md-8">
                        <div class="hero-content text-center">
                            <h1>Contact Us</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-section contact-section overflow-hidden pb-250" id="contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-9">
                        <div class="row justify-content-between">
                            <div class="col-lg-6 order-lg-last">
                                <div class="contact-content">
                                    <div class="contact-form-wrap">
                                        <form @submit.prevent="sendFeedBack()" class="contact-form">
                                            <div class="row gx-3">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <input type="text" v-model="contact_form.name"
                                                            class="input-control" placeholder="Name">
                                                        <div class="text-danger" v-if="errors.name">
                                                            <small>{{ errors.name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <input type="email" v-model="contact_form.email"
                                                            class="input-control" placeholder="Email">
                                                        <div class="text-danger" v-if="errors.email">
                                                            <small>{{ errors.email }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input type="text" v-model="contact_form.subject"
                                                            class="input-control" placeholder="Subject">
                                                        <div class="text-danger" v-if="errors.subject">
                                                            <small>{{ errors.subject }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <textarea v-model="contact_form.message" class="input-control"
                                                            placeholder="Leave a Message"></textarea>
                                                        <div class="text-danger" v-if="errors.message">
                                                            <small>{{ errors.message }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="contact-formbtn-group">
                                                        <button class="button button-primary"
                                                            :disabled="contactFormLoader">
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
                            <div class="col-lg-5 order-lg-first">
                                <div class="contact_left_info">
                                    <div class="main-head mb-2">
                                        <h4 class="subheading">let's talk</h4>
                                        <h2 class="heading">Need Help? <span class="text-primary">Contact Us Now</span></h2>
                                    </div>
                                    <p>Our professional support team is here to help you:</p>
                                    <div class="contactInfo_boxes pt-4">
                                        <div class="contactInfo_item mb-3 bg-light-blue d-flex align-items-center">
                                            <i class="bi bi-envelope"></i>
                                            <div class="contactInfo_content ms-3">
                                                <h5 class="mb-0">Email</h5>
                                                <p class="mb-0"><a href="mailto:support@leadxforms.com">support@leadxforms.com</a></p>
                                            </div>
                                        </div>
                                        <div class="contactInfo_item bg-light-green d-flex align-items-center">
                                            <i class="bi bi-geo-alt"></i>
                                            <div class="contactInfo_content ms-3">
                                                <h5 class="mb-0">Address</h5>
                                                <p class="mb-0">Level 4/260 Queen St, Brisbane City QLD 4000</p>
                                            </div>
                                        </div>
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
import { Head, Link } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

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


}
</script>