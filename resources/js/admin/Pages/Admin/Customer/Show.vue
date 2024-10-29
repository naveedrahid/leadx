<template>
    <Head title="Customer Details" />
    <AppLayout ref="app_layout">
        <div class="container-fluid">
            <Breadcrumb>
                <template v-slot:title>Customer Details</template>
                <li class="breadcrumb-item"><Link :href="route('app.admin.customer.index')" class="text-dark">Customers</Link></li>
                <li class="breadcrumb-item text-muted" aria-current="page">Customer Details</li>
            </Breadcrumb>
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <Link :href="route('app.admin.customer.index')" class="btn btn-primary btn-sm">All Customers</Link>
                    <Link :href="route('app.admin.customer.subscription', [$page.props.id])" class="btn btn-primary btn-sm">All Subscriptions</Link>
                    <Link :href="route('app.admin.customer.subscription.billing', [$page.props.id])" class="btn btn-primary btn-sm" v-if="item.status == 'active'">Billing Information</Link>
                    <Link :href="route('app.admin.customer.subscription.billing.history', [$page.props.id])" class="btn btn-primary btn-sm">Billing History</Link>
                    <Link :href="route('app.admin.customer.subscription.license', [$page.props.id])" class="btn btn-primary btn-sm">Plugin License</Link>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-end gap-2 mb-5">
                        <Link :href="route('app.admin.customer.edit', [$page.props.id])" class="btn btn-info">Edit</Link>
                        <template v-if="!item.other?.is_subscription_active">
                            <button class="btn btn-danger" @click="deleteItem($event, item)">Delete</button>
                        </template>
                        <template v-else>
                            <button class="btn btn-danger" disabled>Delete</button>
                        </template>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-3 d-block">Profile Image:</strong>
                                        <template v-if="item.profile_image_url">
                                            <img :src="item.profile_image_url" :alt="item.fullname" class="rounded-circle" width="80">
                                        </template>
                                        <template v-else>
                                            <span class="text-white d-flex align-items-center justify-content-center text-center rounded fs-6 fw-bold bg-dark" style="width: 80px;height: 80px;">
                                                {{ item.initials }}
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">First Name:</strong>
                                        <span class="fs-3 fw-bold">{{ item.first_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Last Name:</strong>
                                        <span class="fs-3 fw-bold">{{ item.last_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Email:</strong>
                                        <span class="fs-3 fw-bold">{{ item.email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Phone Number:</strong>
                                        <span class="fs-3 fw-bold">{{ item.phone_number }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Status: </strong>
                                        <template v-if="item.status == 'active'">
                                            <span class="fs-3 fw-bold text-primary text-capitalize">{{ item.status }}</span>
                                        </template>
                                        <template v-else>
                                            <span class="fs-3 fw-bold text-dark text-capitalize">{{ item.status }}</span>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 pb-3 border-bottom">
                                        <strong class="fs-4 fw-bolder mb-1 d-block">Date: </strong>
                                        <span class="fs-3 fw-bold">{{ dateFormat(item.created_at, 'DD/MM/YYYY') }}</span>
                                    </div>
                                </div>
                            </div>
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
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';
import moment from 'moment';

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
            item: {},
            loader: false
        };
    },

    methods: {
        dateFormat(date, format, cformat = null) {
            if(cformat) {
                return moment(date, cformat).format(format);
            }

            return moment(date).format(format);
        },

        async getItem() {
            this.loader = true;
            await axios.get(route('api.customer.get.single', [this.$page.props.id]), {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                this.item = $response.data;

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        },

        deleteItem(event, item) {
            let self = this;
            let ele = $(event.target);
            if(ele.prop("tagName").toLowerCase() != 'button') {
                ele = ele.closest('button');
            }

            Swal.fire({
                html: "Please confirm if you want delete this.",
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Not Now',
                customClass: {
                    htmlContainer: 'fs-4',
                    actions: 'm-0 mt-5 gap-3',
                    confirmButton: "btn btn-danger m-0",
                    cancelButton: "btn m-0"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete(item);
                }
            });
        },

        async delete(item) {
            this.loader = true;
            await axios.post(route('api.customer.delete', [item.id]), {}, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.token,
                },
            }).then((response) => {
                let $response = response.data;
                toast.success($response.message);
                this.$cookies.set('lxf-success-msg', $response.message, 10);
                this.$inertia.visit(route('app.admin.customer.index'));

                this.loader = false;
            }).catch((error) => {
                this.loader = false;
                toast.error(error.response.data.message);
            });
        }
    },

    created() {
        this.getItem();
    },

    mounted() {
        this.$refs.app_layout.loadScript();
    }
}
</script>