<template>
    <div class="preloader">
        <img src="/_app_assets/images/logos/x-icon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden bg-light min-vh-100">
            <div class="position-relative z-index-5">
                <div class="row">
                    <div class="col-xl-7">
                        <Link :href="route('home')" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                            <img src="/_app_assets/images/logos/logo.png" width="180" :alt="$page.props.app.name">
                        </Link>
                        <div class="d-none d-xl-flex align-items-center justify-content-center"
                            style="height: calc(100vh - 80px);">
                            <img src="/_app_assets/images/backgrounds/login-security.svg" class="img-fluid" width="500">
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <slot></slot>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <Loader :toggle="loader"></Loader>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Loader from '@/admin/Components/Loader.vue';

export default {
    props: {
        loader: false
    },

    components: {
        Link,
        Loader
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token')
        };
    },

    methods: {
        async isUserLogin() {
            await axios.get(route('api.auth.get.user'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.current_user = $response.data.user;
                this.$cookies.set('lxf-user', this.current_user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));
            }).catch((error) => {
                if(error.response) {
                    this.$cookies.set('lxf-error-msg', error.response.data.message, 10);
                    this.$cookies.remove('lxf-token');
                    this.$cookies.remove('lxf-user');
                    this.$inertia.visit(route('app.auth.login'));
                }
            });
        }
    },

    mounted() {
        $(".preloader").fadeOut();
        
        if(route().current('auth.*')) {
            let successMsg = this.$cookies.get('lxf-success-msg');
            if(successMsg != null) {
                toast.success(successMsg);
                this.$cookies.remove('lxf-success-msg');
            }

            let errorMsg = this.$cookies.get('lxf-error-msg');
            if(errorMsg != null) {
                toast.error(errorMsg);
                this.$cookies.remove('lxf-error-msg');
            }

            let warningMsg = this.$cookies.get('lxf-warning-msg');
            if(warningMsg != null) {
                toast.warning(warningMsg);
                this.$cookies.remove('lxf-warning-msg');
            }
        }
    },

    created() {
        if(this.$cookies.get('lxf-token') || this.$cookies.get('lxf-user')) {
            this.isUserLogin();
        }
    },

    beforeCreate() {
        if(this.$cookies.get('lxf-token') && this.$cookies.get('lxf-user')) {
            let token = this.$cookies.get('lxf-token');
            let user = this.$cookies.get('lxf-user');
            
            if(user.user_type == 'admin') {
                this.$inertia.visit(route('app.admin.dashboard'));
            }

            if(user.user_type == 'customer') {
                this.$inertia.visit(route('app.customer.dashboard'));
            }
        } else {
            this.$cookies.remove('lxf-token');
            this.$cookies.remove('lxf-user');
        }
    }
}
</script>

<style>
@import '/public/_app_assets/css/animate.min.css';
@import '/public/_app_assets/css/style.min.css';
@import '/public/_app_assets/css/custom.css';
</style>