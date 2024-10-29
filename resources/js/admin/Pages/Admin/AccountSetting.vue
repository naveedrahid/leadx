<template>
    <Head title="Account Setting" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Account Setting</template>
                <li class="breadcrumb-item text-muted" aria-current="page">Account Setting</li>
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
                            <h5 class="card-title fw-semibold">Personal Details</h5>
                            <p class="card-subtitle mb-4">To change your personal detail, edit and save from here</p>
                            <form @submit.prevent="accountSetting()">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="first-name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                            <input type="text" id="first-name" class="form-control" v-model="form.first_name" />
                                            <div class="text-danger" v-if="errors.first_name">
                                                {{ errors.first_name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="last-name" class="form-label fw-semibold">Last Name</label>
                                            <input type="text" id="last-name" class="form-control" v-model="form.last_name" />
                                            <div class="text-danger" v-if="errors.last_name">
                                                {{ errors.last_name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label for="email" class="form-label fw-semibold">Email Address</label>
                                            <input type="email" id="email" class="form-control" :value="form.email" disabled>
                                            <div class="text-danger" v-if="errors.email">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label for="phone-number" class="form-label fw-semibold">Phone Number</label>
                                            <input type="tel" id="phone-number" class="form-control" v-model="form.phone_number" />
                                            <div class="text-danger" v-if="errors.phone_number">
                                                {{ errors.phone_number }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="profile-image" class="form-label">Profile Image</label>
                                            <input type="file" id="profile-image" ref="profile_image" class="form-control" @change="handleProfileImage">
                                            <div class="text-danger fs-2" v-if="errors.profile_image">
                                                {{ errors.profile_image }}
                                            </div>
                                            <small class="text-dark d-block my-2">Allowed JPG, GIF or PNG. Max size of 10MB</small>
                                            <template v-if="profile_image != ''">
                                                <div class="d-flex flex-column gap-2" style="width: 100px">
                                                    <img id="profile-image-preview" :src="profile_image_url" class="rounded" :alt="user.fullname" width="100" height="100"/>
                                                    <span class="btn btn-outline-danger btn-sm" @click="clearProfileImage">
                                                        Reset
                                                    </span>
                                                </div>
                                            </template>
                                            <template v-else-if="Object.keys(current_user).length>0 && current_user.profile_image_url && remove_profile_image == false">
                                                <div class="d-flex flex-column gap-2" style="width: 100px">
                                                    <img id="profile-image-preview" :src="current_user.profile_image_url" class="rounded" :alt="user.fullname" width="100" height="100"/>
                                                    <span class="btn btn-danger btn-sm" @click="removeProfileImage">
                                                        Remove
                                                    </span>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <span class="text-white d-inline-flex align-items-center justify-content-center text-center rounded fs-6 fw-bold bg-dark" style="width: 80px;height: 80px;">{{ user.initials }}</span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center gap-2 mt-5">
                                            <button type="submit" class="btn btn-light-primary text-primary">
                                                <i class="ti ti-device-floppy"></i> Save Changes
                                            </button>
                                            <a :href="route('app.admin.dashboard')" class="btn btn-light-danger text-danger">
                                                <i class="ti ti-x"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
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
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    components: {
        Head,
        Link,
        AppLayout,
        Breadcrumb
    },

    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            form: {
                first_name: '',
                last_name: '',
                email: '',
                phone_number: '',
            },
            remove_profile_image: false,
            profile_image: '',
            profile_image_url: '',
            current_user: {},
            errors: {},
            loader: false
        };
    },

    methods: {
        removeProfileImage() {
            this.remove_profile_image = true;
            this.profile_image_url = '';
        },
        
        handleProfileImage(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            if (files[0].size > 10000000) {
                toast.error('Max Size 10MB');
                $('#'+e.target.id).val('');
                return;
            }

            if (files.length) {
                this.profile_image_url = URL.createObjectURL(files[0]);
            }
    
            this.profile_image = files[0];
        },

        clearProfileImage() {
            this.$refs.profile_image.value = '';
            this.profile_image = '';
        },

        async getCurrentUser() {
            await axios.get(route('api.auth.get.user'), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token
                }
            }).then((response) => {
                let $response = response.data;
                this.current_user = $response.data.user;

                this.form.first_name = this.current_user.first_name ? this.current_user.first_name : '';
                this.form.last_name = this.current_user.last_name ? this.current_user.last_name : '';
                this.form.email = this.current_user.email ? this.current_user.email : '';
                this.form.phone_number = this.current_user.phone_number ? this.current_user.phone_number : '';
            }).catch((error) => {
                if (error.response.status == 401) {
                    this.$cookies.remove('lxf-token');
                    this.$cookies.remove('lxf-user');
                    this.$inertia.visit(route('app.auth.login'));
                }
            });
        },

        async accountSetting() {
            this.errors = {};
            this.loader = true;
            
            var fd = new FormData();
            fd.append('profile_image', this.profile_image);
            if(this.remove_profile_image) {
                fd.append('remove_profile_image', true);
            }
            
            _.forEach(this.form, function(value, key) {
                fd.append(key, value);
            });

            await axios.post(route('api.auth.account.setting'), fd, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$cookies.set('lxf-user', $response.data.user, moment(moment()).add(1, 'years').diff(moment(), 'seconds'));
                this.$inertia.visit(route('app.admin.account_setting'));
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
        this.form.first_name = this.user.first_name;
        this.form.last_name = this.user.last_name;
        this.form.email = this.user.email;
        this.form.phone_number = this.user.phone_number;

        this.$refs.app_layout.loadScript();
    },

    created() {
        this.getCurrentUser();
    }
}
</script>