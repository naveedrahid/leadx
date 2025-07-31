<template>

    <Head title="Lead Statuses" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Lead Statuses</template>
                <li class="breadcrumb-item text-muted">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Lead Statuses</li>
            </Breadcrumb>

            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <!-- <Link :href="route('app.customer.lead-statuses.create')" class="btn btn-primary btn-sm"><i
                        class="ti ti-plus"></i> Add
                    New</Link> -->
                    <Link :href="route('app.customer.block-keyword.index')" class="btn btn-dark btn-sm"><i
                        class="ti ti-refresh"></i>
                    Reload</Link>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Website</th>
                                <th>Form</th>
                                <th>Keywords</th>
                                <th>Blocked At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(block, index) in blocked" :key="block.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ block.website }}</td>
                                <td>{{ block.form_id }}</td>
                                <td>
                                    <span v-for="k in block.keywords" :key="k" class="badge bg-light text-dark me-1">{{
                                        k }}</span>
                                </td>
                                <td>{{ block.created_at }}</td>
                            </tr>
                            <tr v-if="blocked.length === 0">
                                <td colspan="5" class="text-center">No blocked keywords found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/admin/Layouts/AppLayout.vue';
import Breadcrumb from '@/admin/Components/Breadcrumb.vue';
import Pagination from '@/admin/Components/Pagination.vue';
import axios from 'axios';

export default {
    components: { Head, Link, AppLayout, Breadcrumb },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            loader: false,
            blocked: [],
        };
    },

    methods: {
        async fetchBlocked() {
            this.loader = true;
            try {
                const res = await axios.get(route('api.block-keyword.index'), {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
                
                this.blocked = res.data.blocked_keywords;
            } catch (err) {
                console.error("Failed to fetch blocked keywords", err);
            } finally {
                this.loader = false;
            }
        }
    },


    mounted() {
        this.fetchBlocked();
        this.$refs.app_layout.loadScript();
    },
};
</script>