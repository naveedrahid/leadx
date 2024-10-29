<template>
    <Head title="Register" />
    <AuthLayout :loader="loader">
        <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center py-5 px-4">
            <div class="col-sm-8 col-md-6 col-xl-9">
                <div class="text-center mb-3">
                    <div class="mb-4">
                        <Link :href="route('home')">
                            <img src="/_app_assets/images/logos/logo-icon.png" width="120" :alt="$page.props.app.name">
                        </Link>
                    </div>
                    <h2 class="mb-2 fs-7 fw-bolder">Signup</h2>
                    <p class="mb-9">Signup to {{ $page.props.app.name }} Account</p>
                </div>
                <form @submit.prevent="register()">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first-name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="first-name" class="form-control" v-model="form.first_name">
                                <div class="text-danger fs-2" v-if="errors.first_name">
                                    {{ errors.first_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last-name" class="form-label">Last Name</label>
                                <input type="text" id="last-name" class="form-control" v-model="form.last_name">
                                <div class="text-danger fs-2" v-if="errors.last_name">
                                    {{ errors.last_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" id="email" class="form-control" v-model="form.email">
                                <div class="text-danger fs-2" v-if="errors.email">
                                    {{ errors.email }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="phone-number" class="form-label">Phone Number</label>
                                <input type="tel" id="phone-number" class="form-control" v-model="form.phone_number">
                                <div class="text-danger fs-2" v-if="errors.phone_number">
                                    {{ errors.phone_number }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input :type="this.isRevealPassword ? 'text' : 'password'" id="password" class="form-control" v-model="form.password">
                                    <span class="input-group-text fs-5" @click="revealPassword()">
                                        <i class="ti" :class="this.isRevealPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                                    </span>
                                    <span class="input-group-text fs-5" @click="generatePassword">
                                        <i class="ti ti-reload"></i>
                                    </span>
                                </div>
                                <div class="text-danger fs-2" v-if="errors.password">
                                    {{ errors.password }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input :type="this.isRevealConfirmPassword ? 'text' : 'password'" id="password_confirmation" class="form-control" v-model="form.password_confirmation">
                                    <span class="input-group-text fs-5" @click="revealConfirmPassword()">
                                        <i class="ti" :class="this.isRevealConfirmPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                                    </span>
                                </div>
                                <div class="text-danger fs-2" v-if="errors.password_confirmation">
                                    {{ errors.password_confirmation }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="profile-image" class="form-label">Profile Image</label>
                                <input type="file" id="profile-image" ref="profile_image" class="form-control" @change="selectProfileImage">
                                <div class="text-danger fs-2" v-if="errors.profile_image">
                                    {{ errors.profile_image }}
                                </div>
                                <small class="text-dark d-block my-2">Allowed JPG, GIF or PNG. Max size of 10MB</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-check form-check-lg">
                                    <input type="checkbox" class="form-check-input me-2" v-model="form.terms" id="term">
                                    <label class="form-check-label" for="term">I agree with <a href="#" target="_blank">terms and conditions</a></label>
                                </div>
                                <div class="text-danger" v-if="errors.terms">
                                    {{ errors.terms }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2" :disabled="loader">
                        <template v-if="loader">
                            <div class="spinner-border spinner-border-sm text-light me-2"></div>
                            Loading...
                        </template>
                        <template v-else>
                            Sign Up
                        </template>
                    </button>
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <p class="fs-4 mb-0 text-dark">Already have an Account?</p>
                        <Link :href="route('app.auth.login')" class="text-primary fw-medium ms-2">Sign In</Link>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <p class="fs-2 text-muted mb-0 fw-medium">Powered by LeadXForm</p>
                    </div>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AuthLayout from "@/admin/Layouts/AuthLayout.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    components: {
        Head,
        Link,
        AuthLayout,
    },

    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                email: '',
                phone_number: '',
                password: '',
                password_confirmation: '',
                is_admin: this.$page.props.is_admin,
                status: 'active',
                terms: false
            },
            profile_image: '',
            isRevealPassword: false,
            isRevealConfirmPassword: false,
            loader: false,
            errors: {}
        };
    },

    methods: {
        revealPassword() {
            this.isRevealPassword = this.isRevealPassword ? false : true;
        },

        revealConfirmPassword() {
            this.isRevealConfirmPassword = this.isRevealConfirmPassword ? false : true;
        },

        generatePassword(event) {
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'span') {
                ele = ele.closest('span');
            }

            ele.find('i').addClass('icon-rotate');
            setTimeout(function() {
                ele.find('i').removeClass('icon-rotate');
            }, 500);

            const length = 8;
            const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*(){}|:<>?[];';
            let password = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }
            
            this.form.password = password;
        },

        selectProfileImage(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            if (files[0].size > 10000000) {
                toast.error('Max Size 10MB');
                $('#'+e.target.id).val('');
                return;
            }
            this.profile_image = files[0];
        },

        async register() {
            this.errors = {};
            this.loader = true;

            var fd = new FormData();
            fd.append('profile_image', this.profile_image);
            _.forEach(this.form, function(value, key) {
                fd.append(key, value);
            });
            
            await axios.post(route('api.auth.signup'), fd, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-token', $response.data.authorisation.token, $response.data.authorisation.expiration);
                this.$cookies.set('lxf-user', $response.data.user, $response.data.authorisation.expiration);
                this.$inertia.visit(route('app.customer.dashboard'));
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
};
</script>
