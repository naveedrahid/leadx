<template>
    <Head title="Change Password" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Change Password</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Change Password</li>
            </Breadcrumb>
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="profile-pic text-center mb-3 mt-3">
                                <template v-if="user.profile_image_url">
                                    <img :src="user.profile_image_url" class="rounded-circle" :alt="user.fullname" width="150" height="150"/>
                                </template>
                                <template v-else>
                                    <span class="text-white d-inline-flex align-items-center justify-content-center text-center rounded fs-6 fw-bold bg-dark" style="width: 80px;height: 80px;">{{ user.initials }}</span>
                                </template>
                                <h4 class="mt-4 mb-1">{{ user.fullname }}</h4>
                                <a :href="'mailto:'+user.email">{{ user.email }}</a>
                            </div>
                            <p v-if="user.phone_number" class="mb-0 fs-3 d-flex align-items-center gap-2 justify-content-center">
                                <i class="ti ti-phone-call fs-5"></i> {{ user.phone_number }}
                            </p>
                        </div>
                        <div class="p-4 border-top mt-3">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <Link :href="route('app.admin.account_setting')" class="link text-dark d-flex align-items-center justify-content-center font-weight-medium">
                                        <i class="ti ti-user-cog me-1 fs-6"></i> Account Setting
                                    </Link>
                                </div>
                                <div class="col-6">
                                    <Link :href="route('app.admin.change_password')" class="link text-dark d-flex align-items-center justify-content-center font-weight-medium">
                                        <i class="ti ti-asterisk me-1 fs-6"></i> Change Password
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card border">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Change Password</h5>
                            <p class="card-subtitle mb-4">To change your password please confirm here</p>
                            <form @submit.prevent="changePassword()">
                                <div class="mb-4">
                                    <label for="current-password" class="form-label fw-semibold">Current Password</label>
                                    <div class="input-group">
                                        <input :type="this.isRevealCurrentPassword ? 'text' : 'password'" id="current-password" class="form-control" v-model="form.current_password" />
                                        <span class="input-group-text fs-5" @click="revealCurrentPassword()">
                                            <i class="ti" :class=" this.isRevealCurrentPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                                        </span>
                                    </div>
                                    <div class="text-danger" v-if="errors.current_password">
                                        {{ errors.current_password }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="new-password" class="form-label fw-semibold">New Password</label>
                                    <div class="input-group">
                                        <input :type="this.isRevealPassword ? 'text' : 'password'" id="new-password" class="form-control" v-model="form.password" />
                                        <span class="input-group-text fs-5" @click="revealNewPassword()">
                                            <i class="ti" :class=" this.isRevealPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                                        </span>
                                        <span class="input-group-text fs-5" @click="generatePassword">
                                            <i class="ti ti-reload"></i>
                                        </span>
                                    </div>
                                    <div class="text-danger" v-if="errors.password">
                                        {{ errors.password }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="new_password" class="form-label fw-semibold">Confirm Password</label>
                                    <div class="input-group">
                                        <input :type="this.isRevealConfirmPassword ? 'text' : 'password'" id="confirmation-password" class="form-control" v-model="form.password_confirmation" />
                                        <span class="input-group-text fs-5" @click="revealConfirmPassword()">
                                            <i class="ti" :class=" this.isRevealConfirmPassword ? 'ti-eye' : 'ti-eye-off'"></i>
                                        </span>
                                    </div>
                                    <div class="text-danger" v-if="errors.password_confirmation">
                                        {{ errors.password_confirmation }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-5">
                                    <button type="submit" class="btn btn-light-primary text-primary">
                                        <i class="ti ti-device-floppy"></i> Save Changes
                                    </button>
                                    <a :href="route('app.admin.dashboard')" class="btn btn-light-danger text-danger">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/admin/Layouts/AppLayout.vue";
import Breadcrumb from "@/admin/Components/Breadcrumb.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb,
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            form: {
                current_password: "",
                password: "",
                password_confirmation: "",
            },
            isRevealCurrentPassword: false,
            isRevealPassword: false,
            isRevealConfirmPassword: false,
            errors: {},
            loader: false
        };
    },

    methods: {
        revealCurrentPassword() {
            this.isRevealCurrentPassword = this.isRevealCurrentPassword ? false : true;
        },

        revealNewPassword() {
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

        async changePassword() {
            this.loader = true;
            this.errors = {};
            await axios.post(route('api.auth.account.change_password'), this.form, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.change_password'));
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

    mounted() {
        this.$refs.app_layout.loadScript();
    }
};
</script>
