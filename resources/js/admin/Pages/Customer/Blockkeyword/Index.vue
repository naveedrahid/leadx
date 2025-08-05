<template>

    <Head title="Block Keywords" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Block Keywords</template>
                <li class="breadcrumb-item active" aria-current="page">Block Keywords</li>
            </Breadcrumb>

            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.block-keyword.create')" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus"></i> Add New
                    </Link>
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
                                <th>Website</th>
                                <th>Form</th>
                                <th>Keywords</th>
                                <th>Blocked At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(block, index) in blocked" :key="block.id">
                                <td>{{ block.website?.website_name || 'N/A' }}</td>
                                <td>{{ block.form_name || 'N/A' }}</td>
                                <td>
                                    <span v-for="k in block.keywords" :key="k.id" class="badge bg-light text-dark me-1">
                                        {{ k.keyword }}
                                    </span>
                                </td>
                                <td>{{ block.created_at || '-' }}</td>
                                <td>
                                    <div class="action-btn d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-sm"
                                            :class="block.is_blocked ? 'btn-danger' : 'btn-primary'"
                                            @click="toggleStatus($event, block)">
                                            {{ block.is_blocked ? 'Blocked' : 'Unblocked' }}
                                        </button>

                                        <Link :href="route('app.customer.block-keyword.edit', [block.id])"
                                            class="btn btn-sm btn-primary">
                                        <i class="fs-4 ti ti-edit"></i> Edit
                                        </Link>
                                    </div>
                                </td>
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
import { toast } from 'vue3-toastify';
import axios from 'axios';

export default {
    components: { Head, Link, AppLayout, Breadcrumb },

    data() {
        return {
            user: this.$cookies.get('lxf-user'),
            token: this.$cookies.get('lxf-token'),
            loader: false,
            blocked: [],
            is_blocked: null,
        };
    },

    methods: {

        async toggleStatus(event = null, item) {

            if (!item || typeof item.is_blocked === 'undefined') {
                toast.error("Something went wrong. Invalid item.");
                return;
            }

            let ele;

            if (event) {
                ele = $(event.target);
                if (ele.prop("tagName")?.toLowerCase() !== 'button') {
                    ele = ele.closest('button');
                }
                ele.prop('disabled', true);
                ele.html('<i class="ti ti-loader rotate"></i> Loading...');
            }

            this.loader = true;

            const newStatus = item.is_blocked ? 0 : 1;

            try {
                const response = await axios.patch(route('api.block-keyword.toggle-status', [item.id]), {
                    is_blocked: newStatus
                }, {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
                toast.success(response.data.message);
                item.is_blocked = newStatus;

            } catch (error) {
                toast.error(error?.response?.data?.message || "Failed to update status");
            } finally {
                if (ele) {
                    ele.prop('disabled', false);
                    ele.html(item.is_blocked === 1 ? 'Blocked' : 'Unblocked');
                }
                this.loader = false;
            }
        },

        async fetchBlocked() {
            this.loader = true;
            try {
                const res = await axios.get(route('api.block-keyword.index'), {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
                console.log(res);
                
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