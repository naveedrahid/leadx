<template>
    <header class="app-header bg-white">
        <nav class="navbar navbar-expand-lg navbar-light justify-content-between">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse"
                        href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
            <div class="d-block d-lg-none">
                <img src="/_app_assets/images/logos/logo.png" class="dark-logo" width="180" alt="" />
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                    <li class="nav-item dropdown" v-if="user">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img">
                                    <template v-if="user.profile_image_url">
                                        <img :src="user.profile_image_url" class="rounded-circle" :alt="user.fullname" width="35" height="35">
                                    </template>
                                    <template v-else>
                                        <span class="text-white d-flex align-items-center justify-content-center text-center rounded-circle fs-2 fw-bold bg-dark" style="width: 35px;height: 35px;">{{ user.initials }}</span>
                                    </template>
                                </div>
                                <div class="ms-2 d-none d-lg-block">
                                    <h5 class="fs-2 mb-0 fw-bold">{{ user.fullname }}</h5>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up border">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                <div class="py-3 px-7 pb-0">
                                    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                </div>
                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                    <template v-if="user.profile_image_url">
                                        <img :src="user.profile_image_url" class="rounded-circle" :alt="user.fullname" width="80" height="80">
                                    </template>
                                    <template v-else>
                                        <span class="text-white d-flex align-items-center justify-content-center text-center rounded-circle fs-6 fw-bold bg-dark" style="width: 80px;height: 80px;">{{ user.initials }}</span>
                                    </template>
                                    <div class="ms-3">
                                        <h5 class="mb-1 fs-3 word-break fw-bold">{{ user.fullname }}</h5>
                                        <p class="mb-0 fs-2 word-break">{{ user.email }}</p>
                                    </div>
                                </div>
                                <div class="message-body">
                                    <Link :href="route('app.customer.account_setting')" class="py-8 px-7 d-flex align-items-center bg-hover-light">
                                        <span class="fs-6"><i class="ti ti-user-cog"></i></span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 fw-semibold">Account Settings</h6>
                                        </div>
                                    </Link>
                                    <Link :href="route('app.customer.change_password')" class="py-8 px-7 d-flex align-items-center bg-hover-light">
                                        <span class="fs-6"><i class="ti ti-asterisk"></i></span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 fw-semibold">Change Password</h6>
                                        </div>
                                    </Link>
                                </div>
                                <div class="p-4 pb-3 border-top">
                                    <button type="button" class="btn btn-danger fw-bolder text-uppercase rounded-1 py-2 px-3 fs-2" @click="logout()">Log Out</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: {
        Link
    },

    data() {
        return {
            user: this.$cookies.get('lxf-user')
        };
    },

    methods: {
        logout() {
            this.$emit('logout');
        }
    }
}
</script>