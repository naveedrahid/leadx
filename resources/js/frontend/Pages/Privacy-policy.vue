<template>
    <Head title="Privacy Policy" />
    <MasterLayout :loader="false">
        <section class="hero-section overflow-hidden" id="hero">
            <div class="container" style="height: 200px">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 col-md-6 text-center">
                        <div class="hero-content">
                            <h1>Coming Soon</h1>
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
    }
};
</script>
