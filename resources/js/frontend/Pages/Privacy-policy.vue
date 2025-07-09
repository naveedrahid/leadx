<template>
    <Head title="Privacy Policy" />
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
                                        <h2 class="heading">Privacy</h2>
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
            errors: {},
            loader: false
        };
    },

};
</script>
