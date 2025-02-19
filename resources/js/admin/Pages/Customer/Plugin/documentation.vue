<template>
    <Head title="Plugin License" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Plugin Documentation</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Plugin Documentation</li>
            </Breadcrumb>

            <div class="row">

                <div class="col-md-6 order-md-1">
                 <h1>Coming Soon</h1>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';
import moment from 'moment';

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb
    },


    methods: {
    },


    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>
