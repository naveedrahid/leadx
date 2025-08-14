<template>

    <Head title="Orders" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Orders </template>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </Breadcrumb>

            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.customer.order.index')" class="btn btn-dark btn-sm"><i
                        class="ti ti-refresh"></i>
                    Reload</Link>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(order, index) in orders" :key="order.id">
                                <td>
                                    #{{ order?.order_data?.id }} <br>
                                    <small>{{ formatDate(order?.created_at) }}</small>
                                </td>
                                <td>{{ order?.order_data?.billing?.first_name }} {{ order?.order_data?.billing?.last_name }}</td>
                                <td>
                                    <span class="badge badge-sm text-capitalize fs-1"
                                        :class="statusClass(order?.order_data?.status)">
                                        {{ order?.order_data?.status || '-' }}
                                    </span>
                                </td>
                                <td>${{ order?.order_data?.total }}</td>
                                <td>
                                    <div class="action-btn d-flex align-items-center gap-2">
                                        <Link :href="route('app.customer.order.show', order.id)"
                                            class="btn btn-sm btn-primary">
                                        <i class="fs-4 ti ti-eye"></i> View
                                        </Link>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="orders.length === 0">
                                <td colspan="5" class="text-center">No orders keywords found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/admin/Layouts/AppLayout.vue";
import Breadcrumb from "@/admin/Components/Breadcrumb.vue";
// import Pagination from "@/admin/Components/Pagination.vue";
// import { toast } from "vue3-toastify";
import axios from "axios";

export default {
    components: { Head, Link, AppLayout, Breadcrumb },
    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            loader: false,
            error: null,
            orders: [],
        };
    },

    methods: {
        formatDate(ts) {
            return new Date(ts).toLocaleString('en-US', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            }).replace(',', ' -');
        },
        statusClass(status) {
            const s = (status || '').toString().toLowerCase();
            if (s === 'completed' || s === 'processing') return 'bg-success';
            if (s === 'pending' || s === 'on-hold') return 'bg-warning';
            if (s === 'failed' || s === 'cancelled' || s === 'refunded')
                return 'bg-danger';
            return 'bg-secondary';
        },
        async fetchOrders() {
            this.loader = true;
            this.error = null;
            try {
                const { data } = await axios.get(route('api.order.index'), {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
                console.log(data);

                this.orders = Array.isArray(data?.orders) ? data.orders : [];
            } catch (err) {
                console.error("Failed to fetch orders", err);
                this.error = err?.response?.data?.message || err?.message || "Failed to load orders.";
                this.orders = [];
            } finally {
                this.loader = false;
            }
        }
    },

    mounted() {
        this.fetchOrders();
        this.$refs.app_layout.loadScript();
    },
};
</script>
