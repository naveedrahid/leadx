<template>

    <Head title="Orders" />
    <AppLayout ref="app_layout" :loader="loader">
        <div class="container-fluid">
            <Breadcrumb>
                <template #title>Order #{{ orderData?.number || order?.id }}</template>
                <li class="breadcrumb-item">
                    <Link :href="route('app.customer.order.index')">Orders</Link>
                </li>
                <li class="breadcrumb-item active">Show</li>
            </Breadcrumb>
            <div class="card">
                <div class="card-body">
                    <!-- <div v-if="hasData" class="d-flex flex-wrap gap-3 mb-3">
                        <div><strong>Order ID:</strong> #{{ orderData.number || order.id }}</div>
                        <div>
                            <strong>Status:</strong>
                            <span class="badge" :class="statusClass(orderData.status)">
                                {{ orderData.status }}
                            </span>
                        </div>
                        <div><strong>Total:</strong> {{ currency }}{{ orderData.total }}</div>
                        <div><strong>Date:</strong> {{ formatDate(order?.created_at) }}</div>
                    </div> -->

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(li, i) in (orderData.line_items || [])" :key="i">
                                <td>{{ li.name }}</td>
                                <td>{{ li.quantity }}</td>
                                <td>{{ li.price }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h6 class="mb-2 mt-4 text-uppercase"><strong>Billing</strong></h6>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>First Name:</strong> {{ orderData?.billing?.first_name }}</li>
                                <li class="list-group-item"><strong>Last Name:</strong> {{ orderData?.billing?.last_name }}</li>
                                <li class="list-group-item"><strong>Email:</strong> {{ orderData?.billing?.email }}</li>
                                <li class="list-group-item"><strong>Phone:</strong> {{ orderData?.billing?.phone }}</li>
                                <li class="list-group-item"><strong>Postcode:</strong> {{ orderData?.billing?.postcode }}</li>
                                <li class="list-group-item"><strong>City:</strong> {{ orderData?.billing?.city }}</li>
                                <li class="list-group-item"><strong>Address:</strong> {{ orderData?.billing?.address }}</li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="mb-2 mt-4 text-uppercase"><strong>Contact</strong></h6>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Address:</strong> {{ orderData?.smoke_order?.contact?.address }}</li>
                                <li class="list-group-item"><strong>Email:</strong> {{ orderData?.smoke_order?.contact?.email }}</li>
                                <li class="list-group-item"><strong>Name:</strong> {{ orderData?.smoke_order?.contact?.name }}</li>
                                <li class="list-group-item"><strong>Phone:</strong> {{ orderData?.smoke_order?.contact?.phone }}</li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="mb-2 mt-4 text-uppercase"><strong>Pricing</strong></h6>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>GST:</strong> {{ orderData?.smoke_order?.pricing?.gst }}</li>
                                <li class="list-group-item"><strong>Quantity:</strong> {{ orderData?.smoke_order?.pricing?.quantity }}</li>
                                <li class="list-group-item"><strong>Subscription:</strong> {{ orderData?.smoke_order?.pricing?.subscription == 1 ?
                                    'Yes'
                                    : 'No' }}
                                </li>
                                <li class="list-group-item"><strong>Subscription Price:</strong> {{
                                    orderData?.smoke_order?.pricing?.subscription_price
                                    }}</li>
                                <li class="list-group-item"><strong>Total (incl. GST):</strong> {{ orderData?.smoke_order?.pricing?.total_incl_gst
                                    }}
                                </li>
                                <li class="list-group-item"><strong>Unit Price:</strong> {{ orderData?.smoke_order?.pricing?.unit_price }}</li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="mb-2 mt-4 text-uppercase"><strong>Property</strong></h6>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Address:</strong> {{ orderData?.smoke_order?.property?.address }}</li>
                                <li class="list-group-item"><strong>Bedrooms:</strong> {{ orderData?.smoke_order?.property?.bedrooms }}</li>
                                <li class="list-group-item"><strong>Email:</strong> {{ orderData?.smoke_order?.property?.email == 1 ? 'Yes' : 'No'
                                    }}
                                </li>
                                <li class="list-group-item"><strong>Storeys:</strong> {{ orderData?.smoke_order?.property?.storeys }}</li>
                                <li class="list-group-item"><strong>Suburb:</strong> {{ orderData?.smoke_order?.property?.suburb }}</li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="mb-2 mt-4 text-uppercase"><strong>Service</strong></h6>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Note:</strong> {{ orderData?.smoke_order?.service?.note }}</li>
                                <li class="list-group-item"><strong>Preferred Date:</strong> {{ orderData?.smoke_order?.service?.preferred_date }}
                                </li>
                                <li class="list-group-item"><strong>Preferred Time:</strong> {{ orderData?.smoke_order?.service?.preferred_time }}
                                </li>
                            </ul>
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

export default {
    components: { Head, Link, AppLayout, Breadcrumb },
    data() {
        return {
            user: this.$cookies.get("lxf-user"),
            token: this.$cookies.get("lxf-token"),
            loader: false,
            error: null,
        };
    },
    computed: {
        hasData() { return Object.keys(this.orderData || {}).length > 0; },
        order() { return this.$page.props?.order || {}; },
        currency() { return this.$page.props?.app?.currency_symbol || '$'; },
        orderData() {
            const raw = this.order?.order_data;
            try { return typeof raw === 'string' ? JSON.parse(raw) : (raw || {}); }
            catch { return {}; }
        },
    },

    methods: {
        formatDate(ts) {
            if (!ts) return "-";
            return new Date(ts).toLocaleString('en-US', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: 'numeric', minute: '2-digit', hour12: true
            }).replace(',', ' -');
        },
        statusClass(status) {
            const s = (status || '').toLowerCase();
            if (['completed', 'processing'].includes(s)) return 'bg-success';
            if (['pending', 'on-hold'].includes(s)) return 'bg-warning';
            if (['failed', 'cancelled', 'refunded'].includes(s)) return 'bg-danger';
            return 'bg-secondary';
        },
    },

    mounted() {
        this.$refs?.app_layout?.loadScript?.();
    },
};
</script>