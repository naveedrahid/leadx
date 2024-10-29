<template>
    <Head title="Create Customer" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Create Customer</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.customer.index')" class="text-dark">Customers</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Add New</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body p-4">
                    <form @submit.prevent="addItem()">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" id="first_name" class="form-control rounded" v-model="form.first_name" placeholder="e.g. John">
                                            <div class="text-danger" v-if="errors.first_name">
                                                <small>{{ errors.first_name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" id="last_name" class="form-control rounded" v-model="form.last_name" placeholder="e.g. Doe">
                                            <div class="text-danger" v-if="errors.last_name">
                                                <small>{{ errors.last_name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email" class="form-control rounded" v-model="form.email" placeholder="e.g. example@mail.com">
                                            <div class="text-danger" v-if="errors.email">
                                                <small>{{ errors.email }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="tel" id="phone_number" class="form-control rounded" v-model="form.phone_number" placeholder="e.g. +0101010101">
                                            <div class="text-danger" v-if="errors.phone_number">
                                                <small>{{ errors.phone_number }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
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
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
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
                                        <div class="form-group mb-3">
                                            <label for="profile-image" class="form-label">Profile Image</label>
                                            <input type="file" id="profile-image" ref="profile_image" class="form-control" @change="handleProfileImage">
                                            <div class="text-danger fs-2" v-if="errors.profile_image">
                                                {{ errors.profile_image }}
                                            </div>
                                            <small class="text-dark d-block my-2">Allowed JPG, GIF or PNG. Max size of 10MB</small>
                                            <template v-if="profile_image != ''">
                                                <div class="d-flex flex-column gap-2" style="width: 100px">
                                                    <img id="profile-image-preview" :src="profile_image_url" class="rounded" width="100" height="100"/>
                                                    <span class="btn btn-outline-danger btn-sm" @click="clearProfileImage">
                                                        Reset
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-2 mt-5">
                                    <button type="submit" class="btn btn-light-primary text-primary">
                                        <i class="ti ti-device-floppy"></i> Save
                                    </button>
                                    <Link :href="route('app.admin.customer.index')" class="btn btn-light-danger text-danger">
                                        <i class="ti ti-x"></i> Cancel
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </form>
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
                password: '',
                password_confirmation: ''
            },
            profile_image: '',
            profile_image_url: '',
            isRevealPassword: false,
            isRevealConfirmPassword: false,
            errors: {},
            loader: false
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

        async addItem() {
            this.errors = {};
            this.loader = true;
            
            var fd = new FormData();
            fd.append('profile_image', this.profile_image);
            _.forEach(this.form, function(value, key) {
                fd.append(key, value);
            });

            await axios.post(route('api.customer.create'), fd, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Authorization: "Bearer " + this.token,
                }
            }).then((response) => {
                let $response = response.data;
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.customer.index'));
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
}
</script>