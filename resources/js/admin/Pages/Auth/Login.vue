<template>
    <Head title="Login" />
    <AuthLayout :loader="loader">
        <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
            <div class="col-sm-8 col-md-6 col-xl-9">
                <div class="text-center mb-3">
                    <div class="mb-4">
                        <Link :href="route('home')">
                            <img src="/_app_assets/images/logos/logo-icon.png" width="120" :alt="$page.props.app.name">
                        </Link>
                    </div>
                    <h2 class="mb-2 fs-7 fw-bolder">Sign In</h2>
                    <p class=" mb-9">Don't have an account? <Link :href="route('home')">Get {{ $page.props.app.name }} Pro</Link>.</p>
                </div>
                <form @submit.prevent="login()">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="email" class="form-control" v-model="form.email">
                        <div class="text-danger" v-if="errors.email">
                            {{ errors.email }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input :type="this.isRevealPassword ? 'text' : 'password'" id="password" class="form-control" v-model="form.password">
                            <span class="input-group-text fs-5" @click="revealPassword()">
                                <i class="ti" :class="this.isRevealPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                            </span>
                        </div>
                        <div class="text-danger" v-if="errors.password">
                            {{ errors.password }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <Link :href="route('app.auth.password.forgot')" class="text-primary fw-medium">Forgot Password ?</Link>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2" :disabled="loader">
                        <template v-if="loader">
                            <div class="spinner-border spinner-border-sm text-light me-2"></div>
                            Loading...
                        </template>
                        <template v-else>
                            Login
                        </template>
                    </button>
                    <div class="d-flex align-items-center justify-content-center">
                        <p class="fs-2 text-muted mb-0 fw-medium">Powered by LeadXForm</p>
                    </div>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AuthLayout from '@/admin/Layouts/AuthLayout.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    components: {
        Head,
        Link,
        AuthLayout
    },

    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            isRevealPassword: false,
            loader: false,
            errors: {}
        };
    },

    methods: {
        revealPassword() {
            this.isRevealPassword = this.isRevealPassword ? false : true;
        },

        async login() {
            this.errors = {};
            this.loader = true;
            await axios.post(route('api.auth.authenticate'), this.form, {
                headers: {
                    "Content-Type": "application/json"
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-token', $response.data.authorisation.token, $response.data.authorisation.expiration);
                this.$cookies.set('lxf-user', $response.data.user, $response.data.authorisation.expiration);
                if($response.data.user.user_type == 'admin') {
                    this.$inertia.visit(route('app.admin.dashboard'));
                }

                if($response.data.user.user_type == 'customer') {
                    this.$inertia.visit(route('app.customer.dashboard'));
                }

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                if(error.response.status == 422) {
                    for (const key in error.response.data.data) {
                        this.errors[key] = error.response.data.data[key][0];
                    }
                }

                toast.error(error.response.data.message);
            });
        },
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