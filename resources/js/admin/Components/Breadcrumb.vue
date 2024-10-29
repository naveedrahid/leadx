<template>
    <div class="card bg-light shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8 text-dark"><slot name="title"></slot></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" v-if="user">
                                <template v-if="user.user_type == 'admin'">
                                    <Link :href="route('app.admin.dashboard')" class="text-dark">Dashboard</Link>
                                </template>
                                <template v-if="user.user_type == 'customer'">
                                    <Link :href="route('app.customer.dashboard')" class="text-dark">Dashboard</Link>
                                </template>
                            </li>
                            <slot></slot>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">  
                        <img src="/_app_assets/images/logos/x-icon.png" class="img-fluid mb-n1" width="200">
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    }
}
</script>